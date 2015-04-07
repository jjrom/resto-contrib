RESTov2 - Administration module
===============================

The Administration module has been founded by the [French Space Agency](http://cnes.fr)

Installation
------------

We suppose that $RESTO2_TARGET is the RESTo v2 installation directory

        cp Administration.php $RESTO2_TARGET/include/resto/Modules

The client doesn't need installation.

Configuration
-------------

Edit $RESTO2_TARGET/include/config.php and add the following within 'modules' section

        /*
         * Administration
         */
         'Administration' => array(
                'activate' => true,
                'route' => 'administration',
                'options' => array()
         )

