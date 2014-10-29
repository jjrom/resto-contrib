<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <?php include 'head.php' ?>
    <body>
         <!-- Header -->
        <?php include 'header.php' ?>
         
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
                        <a href="<?php echo $self->context->baseUrl . 'administration/groups'; ?>" class="button expand"><?php echo $self->context->dictionary->translate('_user_group_rights'); ?></a>
                    </div>
                </li>
                <li>
                    <div class="panel">
                        <a href="<?php echo $self->context->baseUrl . 'administration/users/history'; ?>" class="button expand"><?php echo $self->context->dictionary->translate('_menu_history'); ?></a>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Footer -->
        <?php include 'footer.php' ?>
    </body>
</html>
