# Magento 2 Integration

Magento 2 Integration home. This is ment to be integration core functionality 
on which can depend custon mudules implementations, for maximum reusability.

#install

In your project's composer.json file under 'require' add:
<br />
<code>
"glugox/integration": "*"
</code>

Under 'repositories' add this repo so the composer can get these files:
<br />
<code>{
    "type": "vcs",
    "url": "https://github.com/glugox/integration"
}
</code>

At the time of writing this, you will probably have to change the minimum stabillity to dev:
<br />
<code>
"minimum-stability": "dev"
</code>

At the end simply run:
<br />
<code>
composer update
</code>
