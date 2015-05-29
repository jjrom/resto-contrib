# resto administration module

The Administration module has been founded by the [French Space Agency](http://cnes.fr)

## Installation

### Server side module installation

We suppose that $RESTO_TARGET is the resto installation directory

        cp Administration.php $RESTO_TARGET/include/resto/Modules

Edit $RESTO2_TARGET/include/config.php and add the following within 'modules' section

        /*
         * Administration
         */
         'Administration' => array(
                'activate' => true,
                'route' => 'administration',
                'options' => array()
         )

### Client installation

Copy the client directory under an accessible Web server directory

Set the resto server endpoint within app/app.constant.js file
