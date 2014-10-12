<div class="row fullWidth fixed head" >
    <a href="<?php echo $self->context->baseUrl; ?>" style="margin:15px; float: left; font-weight: bold; font-size: 150%;">RESTo</a>
    <a href="#" style="margin:20px; float: left;"><?php echo $self->context->dictionary->translate('_menu_didacticiel'); ?></a>

    <?php
    if (isset($self->context->user->profile) && $self->context->user->profile['groupname'] === 'admin') {
        echo '<a href="' . $self->context->baseUrl . 'administration/" style="margin:20px; float: left;">' . $self->context->dictionary->translate('_menu_administration') . '</a>';
    }
    ?>
    
    <a href="#" style="margin:20px; float: right;"><?php echo $self->context->dictionary->translate('_menu_connexion'); ?></a>
    <input id="global_search" type="text" placeholder="<?php echo $self->context->dictionary->translate('_menu_globalsearch'); ?>" style="float: right; width: 15%; margin: 10px; display: none;">
</div>
