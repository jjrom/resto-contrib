<?php
$min = 0;
$number = 50;
$keyword = null;
if (filter_input(INPUT_GET, 'min')) {
    $min = filter_input(INPUT_GET, 'min');
}
if (filter_input(INPUT_GET, 'number')) {
    $number = filter_input(INPUT_GET, 'number');
}
if (filter_input(INPUT_GET, 'keyword')) {
    $keyword = filter_input(INPUT_GET, 'keyword');
    $global_search_val = filter_input(INPUT_GET, 'keyword');
} else {
    $keyword = null;
    $global_search_val = $self->context->dictionary->translate('_menu_globalsearch');
}
$usersProfiles = $self->context->dbDriver->getUsersProfiles($keyword, $min, $number);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>RESTo framework</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>js/lib/foundation/foundation.min.css" type="text/css" />
        <script type="text/javascript" src="<?php echo $self->context->baseUrl ?>js/lib/jquery/jquery-1.11.1.min.js"></script>
        <link rel="shortcut icon" href="<?php echo $self->context->baseUrl ?>favicon.ico" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/default/style_min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/<?php echo $self->context->config['theme'] ?>/style.css" type="text/css" />
    </head>
    <body>
        <script type="text/javascript" >
            $(document).ready(function() {

                var self = this;
                $min = <?php echo $min; ?>;
                $number = <?php echo $number; ?>;
                $keyword = "<?php echo $keyword; ?>";

                global_search_val = "<?php echo $global_search_val; ?>";
                //$("#global_search").attr("placeholder", global_search_val);
                $("#global_search").attr("text", global_search_val);
                $("#global_search").show();
                        
                function initialize() {
                    $('input:radio[name=deactivated]').attr('checked', true);
                }

                $("#global_search").change(function() {
                    url = "<?php echo $self->context->baseUrl . 'administration/users'; ?>" + "?keyword=" + $("#global_search").val();
                    window.location = url;
                });

                $("#next").on('click', function() {
                    $min = $min + $number;
                    url = "<?php echo $self->context->baseUrl . 'administration/users'; ?>" + "?min=" + $min + "&number=" + $number + "&keyword=" + $keyword;
                    window.location = url;
                });

                $("#previous").on('click', function() {
                    $min = $min - $number;
                    if ($min < 0) {
                        $min = 0;
                    }
                    url = "<?php echo $self->context->baseUrl . 'administration/users'; ?>" + "?min=" + $min + "&number=" + $number + "&keyword=" + $keyword;
                    window.location = url;
                });

                initialize();
            });
        </script>
        <?php include $self->header; ?>
        <div class="row fullWidth resto-title">

        </div>

        <br/><br/><br/>
        <div class="row" >
            <ul class="small-block-grid-1 large-block-grid-2">
                <?php
                foreach ($usersProfiles as $userProfile) {
                    ?>
                    <li>
                        <div class="panel">
                            <h1><a href="<?php echo $self->context->baseUrl . 'administration/users/' . $userProfile['userid'] ?>"> <?php echo $userProfile['email']; ?></a></h1>
                            <?php
                            echo $self->context->dictionary->translate('_users_groupname') . ' : ' . $userProfile['groupname'] . ' <br/>';
                            echo $self->context->dictionary->translate('_users_username') . ' : ' . $userProfile['username'] . ' <br/>';
                            echo $self->context->dictionary->translate('_users_lastname') . ' : ' . $userProfile['lastname'] . ' <br/>';
                            echo $self->context->dictionary->translate('_users_givenname') . ' : ' . $userProfile['givenname'] . ' <br/>';
                            echo $self->context->dictionary->translate('_users_registrationdate') . ' : ' . $userProfile['registrationdate'] . ' <br/>';
                            echo $self->context->dictionary->translate('_users_activated') . ' : ' . ($userProfile['activated'] == 1 ? 'true' : 'false') . ' <br/>';
                            ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            <div style="text-align: center">
                <?php
                if ($min != 0) {
                    echo '<a id="previous" href="#" class="button">' . $self->context->dictionary->translate('_previousPage') . '</a>';
                }
                if (sizeof($usersProfiles) >= $number) {
                    echo '<a id="next" href="#" style="margin-left: 5px;" class="button">' . $self->context->dictionary->translate('_nextPage') . '</a>';
                }
                ?>
            </div>
        </div>
        <?php include $self->footer; ?>
        <?php exit; ?>
    </body>
</html>
