<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>RESTo framework</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>js/lib/foundation/foundation.min.css" type="text/css" />
        <script type="text/javascript" src="<?php echo $self->context->baseUrl ?>js/lib/jquery/jquery-1.11.1.min.js"></script>
        <link rel="shortcut icon" href="<?php echo $self->context->baseUrl ?>favicon.ico" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/<?php echo $self->context->config['theme'] ?>/style.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/default/style_min.css" type="text/css" />
        <!-- RESTo -->
        <script type="text/javascript" src="<?php echo $self->context->baseUrl ?>/js/resto.js"></script>
    </head>
    <body>
        <script type="text/javascript" >
            $(document).ready(function() {

                var self = this;

                function initialize() {
                    $('#deleteButton').hide();
                    $('.rights').each(function(){
                        if ($(this).attr('rightValue') === 'true'){
                            $(this).css('background-color', 'green');
                        }else{
                            $(this).css('background-color', 'red');
                        }
                    });
                }

                this.activateUser = function(user) {
                    R.showMask();
                    
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + user + "/activate",
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + <?php echo $self->user->profile['userid']; ?>;
                            R.hideMask();
                        },
                        error: function() {
                            R.hideMask();
                            alert("error");
                        }
                    });
                };

                this.deactivateUser = function(user) {
                    R.showMask();
                
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + user + "/deactivate",
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + <?php echo $self->user->profile['userid']; ?>;
                            R.hideMask();
                        },
                        error: function() {
                            R.hideMask();
                            alert("error");
                        }
                    });
                };

                this.deleteUser = function(user) {
                    R.showMask();
                
                    $.ajax({
                        type: "DELETE",
                        url: "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + user,
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>";
                            R.hideMask();
                        },
                        error: function(e) {
                            R.hideMask();
                            alert("error : " + e);
                        }
                    });
                };

                this.deleteRight = function(emailorgroup, collection, featureid) {
                    R.showMask();
                
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + <?php echo $self->user->profile['userid']; ?> + "/rights/delete",
                        async: true,
                        data: {
                            emailorgroup: emailorgroup,
                            collection: collection,
                            featureid: featureid
                        },
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + <?php echo $self->user->profile['userid']; ?>;
                            R.hideMask();
                        },
                        error: function(e) {
                            R.hideMask();
                            alert("error : " + e);
                        }
                    });
                };

                this.setGroup = function(group) {
                    R.showMask();
                
                    $.ajax({
                        type: "POST",
                        async: true,
                        url: "<?php echo $self->context->baseUrl . 'administration/users/' . $self->user->profile['userid']; ?>",
                        dataType: "json",
                        data: {
                            email: "<?php echo $self->user->profile['email']; ?>",
                            groupname: group
                        },
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + <?php echo $self->user->profile['userid']; ?>;
                            R.hideMask();
                        },
                        error: function() {
                            R.hideMask();
                            alert("error");
                        }
                    });

                };
                
                this.updateRights = function(collection, field, valueToSet, obj) {
                    R.showMask();
                
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?php echo $self->context->baseUrl . 'administration/users/' . $self->segments[1] . '/rights/update' ?>",
                        dataType: "json",
                        data: {
                            emailorgroup: '<?php echo $self->user->profile['email']; ?>',
                            collection: collection,
                            field: field,
                            value: valueToSet
                        },
                        success: function() {
                            obj.attr('rightValue', valueToSet);
                            initialize();
                            R.hideMask();
                        },
                        error: function() {
                            R.hideMask();
                            alert("error");
                        }
                    });
                };
                
                
                
                $(".rights").on('click', function(){
                    if ($(this).hasClass('disabled')){
                        
                    }else{
                        collection = $(this).attr('collection');
                        field = $(this).attr('field');
                        rightValue = $(this).attr('rightValue');
                        if (rightValue === 'true'){
                            rightValue = false;
                        }else{
                            rightValue = true;
                        }
                        self.updateRights(collection, field, rightValue, $(this));
                    }
                });

                $("#deleteButton").on('click', function() {
                    self.deleteUser(<?php echo $self->user->profile['userid']; ?>);
                });
                $("#activateButton").on('click', function() {
                    self.activateUser(<?php echo $self->user->profile['userid']; ?>);
                });
                $("#deactivateButton").on('click', function() {
                    self.deactivateUser(<?php echo $self->user->profile['userid']; ?>);
                });
                $("#setGroupAdmin").on('click', function() {
                    self.setGroup('admin');
                });
                $("#setGroupDefault").on('click', function() {
                    self.setGroup('default');
                });

                initialize();
                
                R.init({
                    language: '<?php echo $self->context->dictionary->language; ?>',
                    translation:<?php echo json_encode($self->context->dictionary->getTranslation()) ?>,
                    restoUrl: '<?php echo $self->context->baseUrl ?>',
                    ssoServices:<?php echo json_encode($self->context->config['ssoServices']) ?>,
                    userProfile:<?php echo json_encode(!isset($_SESSION['profile']) ? array('userid' => -1) : array_merge($_SESSION['profile'], array('rights' => $_SESSION['rights']))) ?> 
                });
            });
        </script>
        <?php include $self->header; ?>
        <div class="row fullWidth resto-title">

        </div>

        <br/><br/><br/>
        <div class="row" >
            <ul class="small-block-grid-1 large-block-grid-2" style="text-align: center">
                <li>
                    
                    <h1>
                        <?php echo $self->user->profile['email']; ?>
                    </h1>
                    <p>
                    <?php
                    echo $self->user->profile['groupname'] . ' <br/>';
                    echo $self->user->profile['username'] . ' <br/>';
                    echo $self->user->profile['lastname'] . ' <br/>';
                    echo $self->user->profile['givenname'] . ' <br/>';
                    echo $self->user->profile['registrationdate'] . ' <br/>';
                    ?>
                    </p>        
                </li>
                <li>
                    <div>
                        <ul class="small-block-grid-1 large-block-grid-1">
                            <li>
                                <a href="<?php echo $self->context->baseUrl . 'administration/users/' . $self->user->profile['userid'] . "/history"; ?>" class="button expand"><?php echo $self->context->dictionary->translate('_user_showfullhistory'); ?></a>
                            </li>
                        <?php
                        if ($self->user->profile['groupname'] === 'admin'){
                            
                        }else{
                        ?>
                            <li>
                                <a href="<?php echo $self->context->baseUrl . 'administration/users/' . $self->user->profile['userid'] . "/rights"; ?>" class="button expand"><?php echo $self->context->dictionary->translate('_user_createright'); ?></a>
                            </li>
                            <li>
                                <?php if ($self->user->profile['activated'] == 1) { ?>
                                    <a id="deactivateButton" href="#" class="button expand"><?php echo $self->context->dictionary->translate('_user_deactivate_user'); ?></a>
                                <?php } else { ?>
                                    <a id="activateButton" href="#" class="button expand"><?php echo $self->context->dictionary->translate('_user_activate_user'); ?></a>
                                <?php } ?>
                            </li>
                            <li>
                                <?php if ($self->user->profile['groupname'] == 'admin') { ?>
                                    <a id="setGroupDefault" href="#" class="button expand"><?php echo $self->context->dictionary->translate('_user_set_default_as_group'); ?></a>
                                <?php } else { ?>
                                    <a id="setGroupAdmin" href="#" class="button expand"><?php echo $self->context->dictionary->translate('_user_set_admin_as_group'); ?></a>
                                <?php } ?>
                            </li>
                        </ul>
                        <?php } ?>
                        <a id="deleteButton" class="button expand alert"><?php echo $self->context->dictionary->translate('_user_delete_user'); ?></a>
                    </div>
                </li>
            </ul>
            
            <?php
            $collectionsList = $self->context->dbDriver->listCollections();

            foreach ($collectionsList as $collection) {
                $right = $self->user->getRights($collection['collection']);
                ?>
                <ul class="small-block-grid-1 large-block-grid-1">
                    <div >
                        <fieldset>
                            <legend><?php echo $collection['collection']; ?></legend>
                            <h2>
                                <?php
                                if (isset($self->licenses[$collection['collection']])){
                                    echo "Sign on " . $self->licenses($collection['collection']);
                                }else{
                                    echo "<h3 style=\"color: red;\">Not signed yet</h3>";
                                }
                                ?>
                            </h2>
                            <ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-6">
                                <?php
                                echo '<li><a id="' . $collection['collection'] . 'search" collection="' . $collection['collection'] . '" field="search" class="button expand rights' . (($self->user->profile['groupname'] === 'admin' || $self->user->profile['activated'] != 1) ? ' disabled': '' ) . '" ' . ($right['search'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Search</a></li>';
                                echo '<li><a id="' . $collection['collection'] . 'download" collection="' . $collection['collection'] . '" field="download" class="button expand rights' . (($self->user->profile['groupname'] === 'admin' || $self->user->profile['activated'] != 1) ? ' disabled': '' ) . '" ' . ($right['download'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Download</a></li>';
                                echo '<li><a id="' . $collection['collection'] . 'visualize" collection="' . $collection['collection'] . '" field="visualize" class="button expand rights' . (($self->user->profile['groupname'] === 'admin' || $self->user->profile['activated'] != 1) ? ' disabled': '' ) . '" ' . ($right['visualize'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Visualize</a></li>';
                                echo '<li><a id="' . $collection['collection'] . 'canpost" collection="' . $collection['collection'] . '" field="canpost" class="button expand rights' . (($self->user->profile['groupname'] === 'admin' || $self->user->profile['activated'] != 1) ? ' disabled': '' ) . '" ' . ($right['post'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Post</a></li>';
                                echo '<li><a id="' . $collection['collection'] . 'canput" collection="' . $collection['collection'] . '" field="canput" class="button expand rights' . (($self->user->profile['groupname'] === 'admin' || $self->user->profile['activated'] != 1) ? ' disabled': '' ) . '" ' . ($right['put'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Put</a></li>';
                                echo '<li><a id="' . $collection['collection'] . 'candelete" collection="' . $collection['collection'] . '" field="candelete" class="button expand rights' . (($self->user->profile['groupname'] === 'admin' || $self->user->profile['activated'] != 1) ? ' disabled': '' ) . '" ' . ($right['delete'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Delete</a></li>';
                                ?>
                            </ul>
                        </fieldset>
                    </div>
                </ul>
            <?php } ?>

            <ul class="small-block-grid-1 large-block-grid-1">
                <li>
                    <div>
                        <ul class="small-block-grid-1 large-block-grid-4">
                            <?php
                            
                            foreach ($self->rightsList as $right) {
                                if ($right['featureid']){
                                ?>
                                <div class="panel">
                                    <a id="deleteRightButton" class="button right alert" onclick="deleteRight('<?php echo $self->user->profile['email']; ?>', '<?php echo (isset($right['collection']) ? $right['collection'] : ''); ?>', '<?php echo $right['featureid']; ?>');">X</a>
                                    <h2>
                                        <?php
                                        echo $right['collection'];
                                        echo '  ' . $right['featureid'];
                                        ?>
                                        
                                    </h2>
                                    <ul class="small-block-grid-2 large-block-grid-6">
                                        <?php
                                        echo '<li>search : ' . ($right['search'] == 1 ? 'true' : 'false') . '</li>';
                                        echo '<li>download : ' . ($right['download'] == 1 ? 'true' : 'false') . '</li>';
                                        echo '<li>visualize : ' . ($right['visualize'] == 1 ? 'true' : 'false') . '</li>';
                                        echo '<li>post : ' . ($right['post'] == 1 ? 'true' : 'false') . '</li>';
                                        echo '<li>put : ' . ($right['put'] == 1 ? 'true' : 'false') . '</li>';
                                        echo '<li>delete : ' . ($right['delete'] == 1 ? 'true' : 'false') . '</li>';
                                        ?> 
                                    </ul> 
                                </div>
                                <?php }} ?>
                        </ul>
                    </div>
                </li>
            </ul>
            <ul class="small-block-grid-1 large-block-grid-1">
                <li>
                    <div class="panel">
                        <h1>
                            <?php echo $self->context->dictionary->translate('_user_last_history'); ?>
                        </h1>
                        <?php
                        $options = array(
                            'numberOfResults' => 4
                        );
                        $historyList = $self->context->dbDriver->getHistory($self->user->profile['userid'], $options);
                        foreach ($historyList as $history) {
                            ?>
                            <ul class="small-block-grid-1 large-block-grid-2">
                                <li>
                                    <?php
                                    echo $history['method'] . ' ' . $history['service'] . ' ' . $history['collection'] . ' ' . $history['resourceid'] . ' ' . $history['query'] . ' ' . $history['querytime'] . ' ' . $history['url'] . ' ' . $history['ip'];
                                    ?>
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                </li>
            </ul>

            <?php ?>
        </div>
    </body>
    <?php include $self->footer; ?>
</html>
