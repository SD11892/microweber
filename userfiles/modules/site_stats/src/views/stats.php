<script>
    mw.lib.require('flag_icons');

    $(document).ready(function () {
        $('.sources .source-title').on('click', function () {
            $(this).parent().toggleClass('active');
            $(this).find('id').toggleClass('active');
        });
    });
</script>


<div class="stats-view">
    <div class="mw-ui-col">
        <script>
            $(document).ready(function () {
                mw.tabs({
                    nav: '#demotabsnav .mw-ui-btn-nav-tabs a',
                    tabs: '#demotabsnav .mw-ui-box-content'
                });
            });
        </script>

        <div class="demobox" id="demotabsnav">
            <div class="heading  mw-ui-box">
                <div>Some statistic</div>
                <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
                    <a href="javascript:;" class="mw-ui-btn"><span class="number">726</span>
                        <small>Sites</small>
                    </a>
                    <a href="javascript:;" class="mw-ui-btn active"><span class="number">84</span>
                        <small>Social</small>
                    </a>
                    <a href="javascript:;" class="mw-ui-btn"><span class="number">10.7k</span>
                        <small>Search</small>
                    </a>
                </div>
            </div>

            <div class="sources mw-ui-box">
                <div class="mw-ui-box-content" style="">
                    <ul class="">
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                    </ul>
                </div>
                <div class="mw-ui-box-content" style="display: none;">
                    <ul class="">
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                    </ul>
                </div>
                <div class="mw-ui-box-content" style="display: none">
                    <ul class="">
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mw-ui-col">
        <div class="heading  mw-ui-box">
            <?php print _e('Content'); ?>
        </div>
        <div class="contents mw-ui-box">
            <module type="site_stats/admin" view="content_list"/>
        </div>
    </div>

    <div class="mw-ui-col">
        <div class="heading  mw-ui-box">
            <?php print _e('Visitors'); ?>
        </div>
        <div class="visitors mw-ui-box">
            <module type="site_stats/admin" view="visitors_list"/>
        </div>
    </div>
</div>

<div class="stats-view">
    <div class="mw-ui-col">
        <div class="heading  mw-ui-box">
            <?php print _e('Locations'); ?>
        </div>
        <div class="locations mw-ui-box">
            <module type="site_stats/admin" view="locations_list" />
        </div>
    </div>

    <div class="mw-ui-col">
        <div class="heading  mw-ui-box">
            <?php print _e('Browser language'); ?>
        </div>
        <div class="locations mw-ui-box">
            <module type="site_stats/admin" view="languages_list"  />

        </div>
    </div>
</div>