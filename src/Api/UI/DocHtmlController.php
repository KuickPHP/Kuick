<?php

namespace Kuick\Framework\Api\UI;

use Kuick\Http\Message\Response;

class DocHtmlController
{
    private const TEMPLATE = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>Api Documentation</title>
            <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
        </head>
        <body>
        <div id="swagger-ui"></div>
        <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js" crossorigin></script>
        <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-standalone-preset.js" crossorigin></script>
        <script>
            window.onload = () => {
            window.ui = SwaggerUIBundle({
                url: "/api/doc.json",
                dom_id: "#swagger-ui",
                presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
                ],
                layout: "StandaloneLayout",
            });
            };
        </script>
        </body>
        </html>
    ';

    public function __invoke(): Response
    {
        return new Response(Response::HTTP_OK, [], self::TEMPLATE);
    }
}
