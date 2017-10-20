<footer id="footer">
        <div class="row">
          <div class="col-sm-12 col-md-6">
            ok
            <?php
            // 'Sidebar Footer' = Sidebar name or id
            if ( is_active_sidebar( 'Sidebar Footer' ) ) { ?>
            <ul id="sidebar">
            <?php dynamic_sidebar( 'Sidebar Footer' ); ?>
            </ul>
            <?php } ?>
          </div>
          <div class="col-sm-12 col-md-6">
            <?php
            // 'Sidebar Footer' = Sidebar name or id
            if ( is_active_sidebar( 'Sidebar Footer 2' ) ) { ?>
            <ul id="sidebar">
            <?php dynamic_sidebar( 'Sidebar Footer 2' ); ?>
            </ul>
            <?php } ?>
          </div>

        </div>
</footer>


        <!-- Execution de la fonction wp_footer() obligatoire ! -->
        <?php wp_footer();  ?>
    </body>
</html>
