<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>RESTo framework</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>js/lib/foundation/foundation.min.css" type="text/css" />
        <script type="text/javascript" src="<?php echo $self->context->baseUrl ?>js/lib/jquery/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="<?php echo $self->context->baseUrl ?>js/resto.min.js"></script>
        <link rel="shortcut icon" href="<?php echo $self->context->baseUrl ?>favicon.ico" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/<?php echo $self->context->config['theme'] ?>/style.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/default/style_min.css" type="text/css" />
    </head>
    <body>
        <script type="text/javascript" >
            $(document).ready(function() {

                var self = this;

                function initialize() {
                    $('#deleteButton').hide();
                }

                this.activateUser = function(user) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + user + "/activate",
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + <?php echo $self->user->profile['userid']; ?>;
                        },
                        error: function() {
                            alert("error");
                        }
                    });
                };

                this.deactivateUser = function(user) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + user + "/deactivate",
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + <?php echo $self->user->profile['userid']; ?>;
                        },
                        error: function() {
                            alert("error");
                        }
                    });
                };

                this.deleteUser = function(user) {
                    $.ajax({
                        type: "DELETE",
                        url: "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + user,
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>";
                        },
                        error: function(e) {
                            alert("error : " + e);
                        }
                    });
                };

                this.deleteRight = function(emailorgroup, collection, featureid) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + <?php echo $self->user->profile['userid']; ?> + "/rights/" + collection,
                        async: true,
                        data: {
                            emailorgroup: emailorgroup,
                            collection: collection,
                            featureid: featureid
                        },
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + <?php echo $self->user->profile['userid']; ?>;
                        },
                        error: function(e) {
                            alert("error : " + e);
                        }
                    });
                };

                this.setGroup = function(group) {
                    $.ajax({
                        type: "POST",
                        async: true,
                        url: "<?php echo $self->context->baseUrl . 'administration/users' ?>",
                        dataType: "json",
                        data: {
                            email: "<?php echo $self->user->identifier; ?>",
                            groupname: group
                        },
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>" + <?php echo $self->user->profile['userid']; ?>;
                        },
                        error: function() {
                            alert("error");
                        }
                    });

                };

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
            });
        </script>
        <?php include $self->header; ?>
        <div class="row fullWidth resto-title">

        </div>

        <br/><br/><br/>
        <div class="row" >
            <ul class="small-block-grid-1 large-block-grid-2">
                <li>
                    <div class="panel">
                        <h1>
                            <?php echo $self->context->dictionary->translate('_user_profil'); ?>
                        </h1>
                        <?php
                        echo $self->context->dictionary->translate('_users_email') . ' : ' . $self->user->profile['email'] . ' <br/>';
                        echo $self->context->dictionary->translate('_users_groupname') . ' : ' . $self->user->profile['groupname'] . ' <br/>';
                        echo $self->context->dictionary->translate('_users_username') . ' : ' . $self->user->profile['username'] . ' <br/>';
                        echo $self->context->dictionary->translate('_users_lastname') . ' : ' . $self->user->profile['lastname'] . ' <br/>';
                        echo $self->context->dictionary->translate('_users_givenname') . ' : ' . $self->user->profile['givenname'] . ' <br/>';
                        echo $self->context->dictionary->translate('_users_registrationdate') . ' : ' . $self->user->profile['registrationdate'] . ' <br/>';
                        ?>
                    </div>
                </li>
                <li>
                    <div class="panel">
                        <ul class="small-block-grid-1 large-block-grid-2">
                            <li>
                                <a href="<?php echo $self->context->baseUrl . 'administration/users/' . $self->user->profile['userid'] . "/history"; ?>" class="button expand"><?php echo $self->context->dictionary->translate('_user_showfullhistory'); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $self->context->baseUrl . 'administration/users/' . $self->user->profile['userid'] . "/rights"; ?>" class="button expand"><?php echo $self->context->dictionary->translate('_user_createright'); ?></a>
                            </li>
                        </ul>    
                        <ul class="small-block-grid-1 large-block-grid-2">
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
                        <a id="deleteButton" class="button expand alert"><?php echo $self->context->dictionary->translate('_user_delete_user'); ?></a>
                    </div>
                </li>
            </ul>
            <ul class="small-block-grid-1 large-block-grid-2">

                <li>
                    <div class="panel">
                        <h1>
                            <?php echo $self->context->dictionary->translate('_user_group_rights'); ?>
                        </h1>
                        <?php
                        $rights = $self->user->getRights();
                        echo 'search : ' . ($rights['search'] == 1 ? 'true' : 'false') . ' <br/>';
                        echo 'download : ' . ($rights['download'] == 1 ? 'true' : 'false') . ' <br/>';
                        echo 'visualize : ' . ($rights['visualize'] == 1 ? 'true' : 'false') . ' <br/>';
                        echo 'post : ' . ($rights['post'] == 1 ? 'true' : 'false') . ' <br/>';
                        echo 'put : ' . ($rights['put'] == 1 ? 'true' : 'false') . ' <br/>';
                        echo 'delete : ' . ($rights['delete'] == 1 ? 'true' : 'false') . ' <br/>';
                        ?>
                    </div>
                </li>
                <li>
                    <div class="panel">
                        <h1>
                            <?php echo $self->context->dictionary->translate('_user_signed_licenses'); ?>
                        </h1>
                        <?php
                        $licenses = $self->context->dbDriver->getSignedLicenses($self->user->profile['email']);
                        foreach ($licenses as $license) {
                            ?>
                            <ul class="small-block-grid-1 large-block-grid-2">
                                <li>
                                    <?php
                                    $date = explode(" ", $license['signdate']);
                                    echo $license['collection'] . ', ' . $date[0];
                                    ?>
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                </li>
            </ul>
            <ul class="small-block-grid-1 large-block-grid-1">
                <li>
                    <div class="panel">
                        <h1>
                            <?php echo $self->context->dictionary->translate('_user_private_rights'); ?>
                        </h1>
                        <ul class="small-block-grid-1 large-block-grid-4">
                            <?php
                            $rightsList = $self->context->dbDriver->getRightsList($self->user->profile['email']);
                            foreach ($rightsList as $right) {
                                ?>
                                <div class="panel">
                                    <h3>
                                        <?php
                                        echo 'Collection : ' . $right['collection'] . ' <br/>';
                                        echo 'Featureid : ' . $right['featureid'] . ' <br/>';
                                        ?>
                                    </h3>
                                    <ul class="small-block-grid-1 large-block-grid-3">
                                        <li>
                                            <?php
                                            echo 'filters : ' . $right['filters'] . ' <br/>';
                                            ?>
                                        </li>
                                        <li>
                                            <?php
                                            echo 'search : ' . ($right['search'] == 1 ? 'true' : 'false') . ' <br/>';
                                            echo 'download : ' . ($right['download'] == 1 ? 'true' : 'false') . ' <br/>';
                                            echo 'visualize : ' . ($right['visualize'] == 1 ? 'true' : 'false') . ' <br/>';
                                            ?>
                                        </li>
                                        <li>
                                            <?php
                                            echo 'post : ' . ($right['post'] == 1 ? 'true' : 'false') . ' <br/>';
                                            echo 'put : ' . ($right['put'] == 1 ? 'true' : 'false') . ' <br/>';
                                            echo 'delete : ' . ($right['delete'] == 1 ? 'true' : 'false') . ' <br/>';
                                            ?>
                                        </li>
                                    </ul>
                                    <a id="deleteRightButton" class="button expand alert" onclick="deleteRight('<?php echo $self->user->profile['email']; ?>', '<?php echo (isset($right['collection']) ? $right['collection'] : ''); ?>', '<?php echo $right['featureid']; ?>');">Delete this right</a>
                                </div>
                            <?php } ?>
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
    <?php exit; ?>
</html>
