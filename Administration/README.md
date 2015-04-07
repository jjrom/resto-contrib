RESTov2 - Administration module
===============================

The Administration module has been founded by the [French Space Agency](http://cnes.fr)

Installation
------------

We suppose that $RESTO2_TARGET is the RESTo v2 installation directory

        cp Modules/Administration.php $RESTO2_TARGET/include/resto/Modules
        cp -R themes/default/Modules/Administration $RESTO2_TARGET/themes/default/Modules

Configuration
-------------

Edit $RESTO2_TARGET/include/config.php and add the following within 'modules' section

        /*
         * Administration
         */
         'Administration' => array(
                'activate' => true,
                'route' => 'administration',
                'options' => array(
                    'templatesRoot' => '/Modules/Administration/templates/'
                )
         )

