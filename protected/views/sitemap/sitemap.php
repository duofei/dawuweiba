<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<url>
  <loc><?php echo abu(url('site/index'));?></loc>
  <priority>1.00</priority>
  <changefreq>hourly</changefreq>
</url>
<?php foreach ($data as $v):?>
<url>
  <loc><?php echo $v->absoluteUrl;?></loc>
  <priority>0.80</priority>
  <changefreq>hourly</changefreq>
</url>
<?php endforeach;?>
</urlset>
