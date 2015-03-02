{foreach $urls as $url}
    <url>
        <loc>{$url->loc|escxml}</loc>
        {if $url->lastmod}<lastmod>{$url->lastmod|escxml}</lastmod>{/if}

        {if $url->changefreq}<changefreq>{$url->changefreq|escxml}</changefreq>{/if}

        {if $url->priority}<priority>{$url->priority|escxml}</priority>{/if}

    </url>
{/foreach}
{* endtag is generated by the response *}