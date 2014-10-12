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
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/default/style_min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/<?php echo $self->context->config['theme'] ?>/style.css" type="text/css" />
    </head>
    <body>
        <script type="text/javascript" >
            $(document).ready(function() {

                var self = this;

                function initialize() {
                    $('input:radio[name=deactivated]').attr('checked', true);
                    $('#_alert').hide();
                }
                
                this.alert = function(element){
                    $('#_alert').text('Please set ' + element);
                    $('#_alert').show();
                }
                
                this.addUser = function() {
                    if ($("#email").val() === ''){
                        self.alert('email');
                    }else if ($("#password").val() === ''){
                        self.alert('password');
                    }else if ($("#username").val() === ''){
                        self.alert('username');
                    }else if ($("#givenname").val() === ''){
                        self.alert('givenname');
                    }else if ($("#lastname").val() === ''){
                        self.alert('lastname');
                    }else if ($("#password").val() !== $("#passwordConfirm").val()){
                        self.alert('two password fields equals...');
                    }else{
                        $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?php echo $self->context->baseUrl . 'administration/users' ?>",
                        dataType: "json",
                        data: {
                            email: $("#email").val(),
                            groupname: $('select[name=groupname]').val(),
                            password: $("#password").val(),
                            username: $("#username").val(),
                            givenname: $('#givenname').val(),
                            lastname: $('#lastname').val(),
                            activated: $('input[name=activated]:checked').val()
                        },
                        error: function() {
                            alert('error');
                        },
                        success: function() {
                            window.location = "<?php echo $self->context->baseUrl . 'administration/users/' ?>";
                        }
                    });
                }


            };

            $("#_save").on('click', function() {
                $('#_alert').hide();
                self.addUser();
            });

            $("#_alert").on('click', function() {
                $('#_alert').hide();
            });

            $("#activated").on('click', function() {
                $('input:radio[name=activated]').attr('checked', true);
                $('input:radio[name=deactivated]').attr('checked', false);
            });

            $("#deactivated").on('click', function() {
                $('input:radio[name=activated]').attr('checked', false);
                $('input:radio[name=deactivated]').attr('checked', true);
            });

            initialize();
        });
        </script>
        <?php include $self->header; ?>
        <div class="row fullWidth resto-title">

        </div>

        <br/><br/><br/>
        <div class="row" >
            <a id="_alert" href="#" class="button expand alert hide"></a>
            <form>
                <fieldset>
                    <legend><?php echo $self->context->dictionary->translate('_user_profil'); ?></legend>

                    <label><?php echo $self->context->dictionary->translate('_users_email'); ?>
                        <input id="email" type="text" placeholder="<?php echo $self->context->dictionary->translate('_users_email'); ?>...">
                    </label>
                    <label><?php echo $self->context->dictionary->translate('_users_lastname'); ?>
                        <input id="lastname" type="text" placeholder="<?php echo $self->context->dictionary->translate('_users_lastname'); ?>...">
                    </label>
                    <label><?php echo $self->context->dictionary->translate('_users_username'); ?>
                        <input id="username" type="text" placeholder="<?php echo $self->context->dictionary->translate('_users_username'); ?>...">
                    </label>
                    <label><?php echo $self->context->dictionary->translate('_users_givenname'); ?>
                        <input id="givenname" type="text" placeholder="<?php echo $self->context->dictionary->translate('_users_givenname'); ?>...">
                    </label>
                    <label><?php echo $self->context->dictionary->translate('_password'); ?>
                        <input id="password" type="password" placeholder="<?php echo $self->context->dictionary->translate('_password'); ?>...">
                    </label>
                    <label><?php echo $self->context->dictionary->translate('_retypePassword'); ?>
                        <input id="passwordConfirm" type="password" placeholder="<?php echo $self->context->dictionary->translate('_retypePassword'); ?>...">
                    </label>
                </fieldset>
                <fieldset>
                    <legend><?php echo $self->context->dictionary->translate('_group'); ?></legend>
                    <label><?php echo $self->context->dictionary->translate('_history_select_group_name'); ?>
                        <select id="groupname" name="groupname">
                            <option value="unregistered"><?php echo $self->context->dictionary->translate('_unregistered'); ?></option>
                            <option value="default"><?php echo $self->context->dictionary->translate('_default'); ?></option>
                            <option value="admin"><?php echo $self->context->dictionary->translate('_admin'); ?></option>
                        </select>
                    </label>
                </fieldset>
                <fieldset>
                    <legend><?php echo $self->context->dictionary->translate('_users_activated'); ?></legend>
                    <input type="radio" name="activated" value="true" id="activated"><label for="activated"><?php echo $self->context->dictionary->translate('_users_activated'); ?></label>
                    <input type="radio" name="deactivated" value="false" id="deactivated"><label for="deactivated"><?php echo $self->context->dictionary->translate('_users_deactivated'); ?></label>
                </fieldset>
            </form> 
            <a id="_save" href="#" class="button expand"><?php echo $self->context->dictionary->translate('_save_user'); ?></a>
        </div>
    </body>
    <?php include $self->footer; ?>
    <?php exit; ?>
</html>
