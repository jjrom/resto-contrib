<?php
    $_noSearchBar = true;
    $_noMap = true;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <?php include 'head.php' ?>
    <body>
        
        <!-- Header -->
        <?php include 'header.php' ?>
        
        <div class="row fullWidth resto-title">

        </div>

        <br/><br/><br/>
        <div class="row" >
            <ul class="small-block-grid-1 large-block-grid-1">
                <?php
                foreach ($self->collections as $collection) {
                    ?>
                    <li>
                        <div>
                            <h1 style="text-align: center;"><?php echo $collection['collection']; ?></h1>
                        
                        <?php
                        //foreach ($self->groups as $group) {
                        $group['groupname'] = 'default';
                            $restoRights = new RestoRights($group['groupname'], $group['groupname'], $self->context->dbDriver);
                            $right = $restoRights->getRights($collection['collection']);
                            ?>
                            <ul class="small-block-grid-1 large-block-grid-1">
                                <fieldset>
                                    <legend><?php echo $group['groupname']; ?></legend>
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
                                </fieldset>
                            </ul>
                        <?php //} ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- Footer -->
        <?php include 'footer.php' ?>
        
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
                        url: "<?php echo $self->context->baseUrl;?>administration/collections",
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
                        error: function(e) {
                            R.hideMask();
                            alert('error : ' + e['responseJSON']['ErrorMessage']);
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
                    userProfile:<?php echo json_encode(!isset($_SESSION['profile']) ? array('userid' => -1) : array_merge($_SESSION['profile'], array())) ?> 
                });
            });
        </script>
    </body>
</html>
