<header>
    
    <span id="logo">
        <a title="<?php echo $self->context->dictionary->translate('_home'); ?>" href="<?php echo $self->context->baseUrl ?>">resto</a>
    </span>
    
    <span style="margin-left: 20px; margin-top: 15px; float: left; color: white;">
        <?php
        $i = 0;
        $url = $self->context->baseUrl . 'administration';
        echo '<a href="' . $url . '">administration</a>';
        while ($i < sizeof($self->segments)) {
            $url = $url . '/' . $self->segments[$i];
            echo ' > <a href="' . $url . '">' . $self->segments[$i] . '</a>';
            ++$i;
        }
        ?>
    </span>
    
    <nav>
        <ul class="no-bullet">
            
            <?php if (!isset($_noSearchBar)) { ?>
            <li><span class="resto-search"><input type="text" id="search" name="q" placeholder="<?php echo $self->context->dictionary->translate('_menu_search'); ?>" value="<?php echo isset($self->context->query['q']) ? $self->context->query['q'] : ''; ?>"></span><li>
            <?php
            if ($self->context->dictionary->language) {
                echo '<input type="hidden" name="lang" value="' . $self->context->dictionary->language . '" />';
            }
            ?>
            <?php } ?>
            <li></li>
            <?php if ($self->context->user->profile['userid'] === -1) { ?>
            <li title="<?php echo $self->context->dictionary->translate('_menu_connexion'); ?>" class="link viewUserPanel"><?php echo $self->context->dictionary->translate('_menu_connexion'); ?></li>
            <?php } else { ?>
            <li title="<?php echo $self->context->dictionary->translate('_menu_profile'); ?>" class="link gravatar center viewUserPanel"></li>
            <?php } ?>
        </ul>
    </nav>
</header>

        
