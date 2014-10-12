<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>RESTo framework</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
        <link rel="shortcut icon" href="<?php echo $self->context->baseUrl ?>favicon.ico" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>js/lib/foundation/foundation.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/<?php echo $self->context->config['theme'] ?>/style.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/default/style_min.css" type="text/css" />
    </head>
    <body>
        <?php include $self->header; ?>
        <div class="row fullWidth resto-title">

        </div>
        <br/>
        <br/>
        <br/>
        <div class="row" style="text-align: center">
            <h1>Administration</h1>
            <ul class="small-block-grid-1 large-block-grid-3">
                <li>
                    <div class="panel">
                        <a href="<?php echo $self->context->baseUrl . 'administration/users/'; ?>" class="button expand"><?php echo $self->context->dictionary->translate('_start_users'); ?></a>
                    </div>
                </li>
                <li>
                    <div class="panel">
                        <a href="<?php echo $self->context->baseUrl . 'administration/users/creation'; ?>" class="button expand"><?php echo $self->context->dictionary->translate('_menu_create_user'); ?></a>
                    </div>
                </li>
                <li>
                    <div class="panel">
                        <a href="<?php echo $self->context->baseUrl . 'administration/users/history'; ?>" class="button expand"><?php echo $self->context->dictionary->translate('_menu_history'); ?></a>
                    </div>
                </li>
            </ul>
        </div>
        <?php include $self->footer; ?>
        <?php exit; ?>
    </body>
</html>
