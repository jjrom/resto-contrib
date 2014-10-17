<?php
$groups = $self->context->dbDriver->listGroups();
$collections = $self->context->dbDriver->listCollections();
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
        <!-- RESTo -->
        <script type="text/javascript" src="<?php echo $self->context->baseUrl ?>/js/resto.js"></script>
    </head>
    <body>
        <script type="text/javascript" >
            $(document).ready(function() {

                var self = this;

                function initialize() {
                    $('.rights').each(function(){
                        if ($(this).attr('rightValue') === 'true'){
                            $(this).css('background-color', 'green');
                        }else{
                            $(this).css('background-color', 'red');
                        }
                    });
                }
                
                this.updateRights = function(groupname, collection, field, valueToSet, obj) {
                    R.showMask();
                
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?php echo $self->context->baseUrl;?>administration/groups",
                        dataType: "json",
                        data: {
                            emailorgroup: groupname,
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
                    groupname = $(this).attr('groupname');
                    collection = $(this).attr('collection');
                    field = $(this).attr('field');
                    rightValue = $(this).attr('rightValue');
                    if (rightValue === 'true'){
                        rightValue = false;
                    }else{
                        rightValue = true;
                    }
                    self.updateRights(groupname, collection, field, rightValue, $(this));
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
            <ul class="small-block-grid-1 large-block-grid-1">
                <?php
                foreach ($collections as $collection) {
                    ?>
                    <li>
                        <div class="panel">
                            <h1><?php echo $collection['collection']; ?></h1>
                        
                        <?php
                        $collectionsList = $self->context->dbDriver->listCollections();

                        foreach ($groups as $group) {
                            $restoRights = new RestoRights($group['groupname'], $group['groupname'], $self->context->dbDriver);
                            $right = $restoRights->getRights($collection['collection']);
                            ?>
                            <ul class="small-block-grid-1 large-block-grid-1">
                                <div >
                                    <h2>
                                        <?php
                                        echo $group['groupname'];
                                        ?>
                                    </h2>
                                    <ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-6">
                                        <?php
                                        echo '<li><a groupname="' . $group['groupname'] . '" collection="' . $collection['collection'] . '" field="search" class="button expand rights" ' . ($right['search'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Search</a></li>';
                                        echo '<li><a groupname="' . $group['groupname'] . '" collection="' . $collection['collection'] . '" field="download" class="button expand rights" ' . ($right['download'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Download</a></li>';
                                        echo '<li><a groupname="' . $group['groupname'] . '" collection="' . $collection['collection'] . '" field="visualize" class="button expand rights" ' . ($right['visualize'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Visualize</a></li>';
                                        echo '<li><a groupname="' . $group['groupname'] . '" collection="' . $collection['collection'] . '" field="canpost" class="button expand rights" ' . ($right['post'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Post</a></li>';
                                        echo '<li><a groupname="' . $group['groupname'] . '" collection="' . $collection['collection'] . '" field="canput" class="button expand rights" ' . ($right['put'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Put</a></li>';
                                        echo '<li><a groupname="' . $group['groupname'] . '" collection="' . $collection['collection'] . '" field="candelete" class="button expand rights" ' . ($right['delete'] == 1 ? 'rightValue="true" style="background-color: green;"' : 'rightValue="false" style="background-color: red;"') . '>Delete</a></li>';
                                        ?>
                                    </ul>
                                </div>
                            </ul>
                        <?php } ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <?php include $self->footer; ?>
        <?php exit; ?>
    </body>
</html>
