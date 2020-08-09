# M2Boilerplate ServiceWorker

A Magento 2 extension that adds [Service Worker](https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API) support.

Features:
* Fully customisable Service Worker script
* Cache-first approach for page assets resulting in faster page loads

## Installation

Add this extension to your Magento installation with Composer:

    composer require m2-boilerplate/module-service-worker/module-service-worker

## Usage

### Configuration

The Service Worker is configured and enabled by default. However, Service Workers require the site to run on HTTPS.

Features can be customised in *Stores > Configuration > General > Web > Service Worker Settings*.

### Adding more logic

If the base service worker file doesn't fit all of your needs you can easily add more logic using the layout system, targeting the `serviceworker_index_js` handle:

    <?xml version="1.0"?>
    <page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
        <body>
            <referenceBlock name="serviceworker">
                <block name="morelogic.logic" template="M2Boilerplate_MoreLogic::logic.phtml" />
            </referenceBlock>
        </body>
    </page>

## FAQ

### Why do page assets not appear to be cached when Magento is in default/developer mode?

Magento 2 uses a timestamp version string in the URL for static files to allow busting browser cache when the static content gets updated. In developer mode, this version string is updated for every unique page request. This means that from the browser's perspective, the static assets, such as CSS files, are completely different on each page, even though the content is the same. Therefore, when the assets get cached by the Service Worker in Magento 2 developer mode, they only get cached for that specific page. Production mode only generates the static assets through command line and keeps the version timestamp fixed, so it doesn't experience this issue.

### Testing Service Workers on Chrome

Chrome is very strict about security and only allows Service Workers on localhost, or on an HTTPS site with a valid certificate. To bypass these restrictions for testing, use the `--ignore-certificate-errors` and `--unsafely-treat-insecure-origin-as-secure` flags to run a less secure copy of Chrome:

    /Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome \
        --user-data-dir=/tmp/chrome \
        --ignore-certificate-errors \
        --unsafely-treat-insecure-origin-as-secure=https://m2-meanbee-serviceworker.docker/
