<?php

namespace Br\ApiDocsPackage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controller;

class ApiDocsController extends Controller
{
    public function index()
    {
        $docs = DB::table('api_docs')->get();

        return $this->renderView('api-docs::api_docs', compact('docs'));
    }

    public function show($id)
    {
        $doc = DB::table('api_docs')->find($id);

        if (!$doc) {
            abort(404, 'API documentation not found');
        }

        $headers     = json_decode($doc->header, true) ?? [];
        $body        = json_decode($doc->body, true) ?? [];
        $curlCommand = $this->buildCurlCommand($doc->route, $doc->method, $headers, $body);

        return $this->renderView('api-docs::api_doc_detail', compact('doc', 'curlCommand', 'headers', 'body'));
    }

    private function buildCurlCommand($url, $method, $headers, $body)
    {
        $command = "curl --location " . escapeshellarg($url);

        if (isset($headers['authorization'])) {
            $authValue = is_array($headers['authorization']) ? $headers['authorization'][0] : $headers['authorization'];
            $command   .= " \\\n--header " . escapeshellarg("Authorization: " . $authValue);
        }

        if (isset($headers['accept'])) {
            $acceptValue = is_array($headers['accept']) ? $headers['accept'][0] : $headers['accept'];
            $command     .= " \\\n--header " . escapeshellarg("Accept: " . $acceptValue);
        } else {
            $command .= " \\\n--header 'Accept: application/json, text/plain, */*'";
        }

        if ($method !== 'GET') {
            $command .= " \\\n-X " . escapeshellarg($method);
        }

        if (!empty($body)) {
            $jsonBody    = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $escapedBody = escapeshellarg($jsonBody);
            $command     .= " \\\n--data " . $escapedBody;
        }

        return $command;
    }

    private function renderView($view, $data)
    {
        $layout = config('api-docs.layout');

        if (empty($layout)) {
            return View::make($view, $data)->render(function ($view, $content) {
                return $this->getDefaultHtml($content);
            });
        }
        return view($view, $data)->extends($layout);
    }

    private function getDefaultHtml($content)
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        $content
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
    }
}