<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <?php include realpath(dirname(__FILE__)) . '/../../../templates/' . 'head.php' ?>
    <body style="overflow-x: hidden;">
        
        <!-- Header -->
        <?php include realpath(dirname(__FILE__)) . '/../../../templates/' . 'header.php' ?>
        
        <!-- Breadcrumb -->
        <?php include 'breadcrumb.php' ?>
        
        <div class="row" >
            <ul class="small-block-grid-1 large-block-grid-2" style="padding-top: 15px">
                <?php
                foreach ($self->usersProfiles as $userProfile) {
                    ?>
                    <li>
                        <?php if($userProfile['groupname'] === 'admin'){?>
                        <div class="panel" style="background-color: #BDBDBD">
                            <h1><?php echo $userProfile['email']; ?></h1>
                        <?php }else{ ?>
                        <div class="panel">
                            <h1><a href="<?php echo $self->context->baseUrl . 'administration/users/' . $userProfile['userid'] ?>"> <?php echo $userProfile['email']; ?></a></h1>
                        <?php } ?>
                            <?php
                            echo $self->context->dictionary->translate('_a_groupname') . ' : ' . $userProfile['groupname'] . ' <br/>';
                            echo $self->context->dictionary->translate('_a_username') . ' : ' . $userProfile['username'] . ' <br/>';
                            echo $self->context->dictionary->translate('_a_lastname') . ' : ' . $userProfile['lastname'] . ' <br/>';
                            echo $self->context->dictionary->translate('_a_givenname') . ' : ' . $userProfile['givenname'] . ' <br/>';
                            echo $self->context->dictionary->translate('_a_registrationdate') . ' : ' . $userProfile['registrationdate'] . ' <br/>';
                            echo $self->context->dictionary->translate('_a_activated') . ' : ' . ($userProfile['activated'] == 1 ? 'true' : 'false') . ' <br/>';
                            ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            <div style="text-align: center">
                <?php
                if ($self->min != 0) {
                    echo '<a id="previous" href="#" class="button">' . $self->context->dictionary->translate('_previousPage') . '</a>';
                }
                if (sizeof($self->usersProfiles) >= $self->number) {
                    echo '<a id="next" href="#" style="margin-left: 5px;" class="button">' . $self->context->dictionary->translate('_nextPage') . '</a>';
                }
                ?>
            </div>
        </div>
        <!-- Footer -->
        <?php include realpath(dirname(__FILE__)) . '/../../../templates/' . 'footer.php' ?>
        
        <script type="text/javascript" >
            $(document).ready(function() {

                var self = this;
                $min = <?php echo $self->min; ?>;
                $number = <?php echo $self->number; ?>;
                $keyword = "<?php echo $self->keyword; ?>";

                global_search_val = "<?php echo $self->global_search_val; ?>";
                //$("#global_search").attr("placeholder", global_search_val);
                $("#search").attr("text", global_search_val);
                $("#search").show();
                        
                function initialize() {
                    $('input:radio[name=deactivated]').attr('checked', true);
                }

                $("#search").change(function() {
                    url = "<?php echo $self->context->baseUrl . 'administration/users'; ?>" + "?keyword=" + $("#search").val();
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
                
                $(document).ready(function() {
                    Resto.init({
                        "translation":<?php echo json_encode($self->context->dictionary->getTranslation()) ?>,
                        "language":'<?php echo $self->context->dictionary->language; ?>',
                        "restoUrl":'<?php echo $self->context->baseUrl ?>',
                        "ssoServices":<?php echo json_encode($self->context->config['ssoServices']) ?>,
                        "userProfile":<?php echo json_encode(!isset($_SESSION['profile']) ? array('userid' => -1) : array_merge($_SESSION['profile'], array('rights' => isset($_SESSION['rights']) ? $_SESSION['rights'] : array()))) ?>
                    });
                });
                
            });
        </script>
    </body>
</html>
