<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <?php include 'head.php' ?>
    <body>
        
        <!-- Header -->
        <?php include 'header.php' ?>
        <div class="row fullWidth resto-title"></div>

        <br/><br/><br/>
        <div class="row" >
            <ul class="small-block-grid-1 large-block-grid-2">
                <?php
                foreach ($self->usersProfiles as $userProfile) {
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
        <?php include 'footer.php' ?>
        
        <script type="text/javascript" >
            $(document).ready(function() {

                var self = this;
                $min = <?php echo $self->min; ?>;
                $number = <?php echo $self->number; ?>;
                $keyword = "<?php echo $self->keyword; ?>";

                global_search_val = "<?php echo $self->global_search_val; ?>";
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
    </body>
</html>
