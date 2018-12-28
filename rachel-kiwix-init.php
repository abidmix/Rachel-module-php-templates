    <?php

        # kiwix-based modules have to point to a different port, so they
        # need a complete URL with hostname. They also need to figure out
        # the name of the zim file since it changes each updates.
        # We do all that here: 

        $host    = "//$_SERVER[HTTP_HOST]:81";

        $zims = scandir( __DIR__ . "/data/content" );
        $zim = "";
        foreach ($zims as $z) {
            if (preg_match("/\.zim/", $z)) {
                $zim = preg_replace("/\.zim.*?$/", "", $z);
                break;
            }
        }

        $baseurl = "$host/$zim";

        $langcode = substr( basename(__DIR__), 0, 2);
        $searchwords = array(
            'en' => "Search",
            'es' => "Buscar",
            'fr' => "Rechercher",
            'pt' => "Pesquisar",
            'de' => "Durchsuchen",
        );
        if (isset($searchwords[$langcode])) {
            $searchword = $searchwords[ $langcode ];
        } else {
            # magnifying glass icon - but makes button size weird
            $searchword = "&nbsp;&#x1F50D;&nbsp;";
        }

    ?>

    <!-- search box points to kiwix search -->
    <form action="<?php echo $host ?>/search" id="<?php echo $zim ?>-search-form">
      <div>
        <input type="text" id="<?php echo $zim ?>-search-box" name="pattern">
        <input type="submit" name="search" value="<?php echo $searchword ?>">
        <input type="hidden" name="content" value="<?php echo $zim ?>">
      </div>
    </form>

    <!-- script points to kiwix search suggestions-->
    <script>
      $(function() {
        $( "#<?php echo $zim ?>-search-box" ).autocomplete({
          source: "<?php echo $host ?>/suggest?content=<?php echo $zim ?>",
          dataType: "json",
          cache: false,
          select: function(event, ui) {
            $( "#<?php echo $zim ?>-search-box" ).val(ui.item.value);
            $( "#<?php echo $zim ?>-search-form" ).submit();
          },
        });
      });
    </script>
