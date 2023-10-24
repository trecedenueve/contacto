<?php
// Exit when accessed directly
defined( 'ABSPATH' ) or die ( 'Permission denied' ); 
?>


<header class="header__contact-form">
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('style-form.css', __FILE__); ?>">
</header>


<form id="contact-form" name="contact-form" method="post"  
    action="<?php echo get_permalink(); ?>#contact-form">
        <?php // Comprobamos si se ha enviado
        if ( isset( $_POST[ 'btn-submit' ])) {
            // Errores en $reg_errors
            global $reg_errors;
            $reg_errors = new WP_Error;

            //variables para los campos y sanitize
            $tdn_name = sanitize_text_field( $_POST[ 'tdn_name' ]);
            $tdn_email = sanitize_email( $_POST[ 'tdn_email']);
            $tdn_message = sanitize_text_field( $_POST[ 'tdn_message' ]);
                    
            //Campos Nombre, email y mensaje son obligatorios
            if( empty( $tdn_name )) {
                $reg_errors->add( "empty-name", "El campo nombre es obligatorio");
                }

            if( empty( $tdn_email )) {
                $reg_errors->add( "empty-email", "El campo e-mail es obligatorio");
                }
                // en el mail comprobamos ademas que el formato con la funcion de wp "is_email"
            if( !is_email($tdn_email)) {
                    $reg_errors->add( "invalid-email", "El formato del email no es correcto");
                    }
                    
            if( empty( $tdn_message )) {
                    $reg_errors->add( "empty-message", "El campo de mensaje es obligatorio");
                    }

            //Si todo esta bien enviamos el formulario
            if( count($reg_errors->get_error_messages()) == 0) {
                //Email destino
                $recipient = get_option('admin_email');

                //asunto del mail
                $subjet = 'Formulario de contacto desde ' . get_bloginfo( 'name' );

                //la dirección de envio es la de nuestro wp añadimos un header para contestar
                $headers = "Reply-to: " . $tdn_name . " <" . $tdn_email . ">\r\n";

                //Cuerpo del mail
                $message = "Nombre: " . $tdn_name . "<br>";
                $message .= "E-mail: " . $tdn_email . "<br>";
                $message .= "Mensaje: " . nl2br($tdn_message) ."<br>";
                        
                //Filtro para enviar el mail en HTML
                add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ));

                        //Ahora si, enviamos el email
                        $envio = wp_mail( $recipient, $subjet, $message, $header, $attacments );

                    }   
                    //Si el envio es correcto 
                    if( $envio ) {
                        unset( $tdn_name );
                        unset( $tdn_email );
                        unset( $tdn_message );
                        ?>
                        <div class="alert alert-success alert-dimissable">
                            <button type="button" class="close" data-dismiss="alert" 
                                aria-hidden="true">
                                &times;
                            </button>
                            Formulario enviado correctamente
                            <?php echo $recipient(); ?>
                        </div>
                        <?php 
                    } else {
                        ?>
                        <div class="alert alert-danger alert-disimissable">
                            <button type="button" class="close" data-dismiss="alert" 
                                aria-hidden="true">
                                &times;
                            </button>
                            El formulario no se ha podido enviar. Vuelve a intentarlo
                        </div>
                        <?php
                    }
                }
                ?>
                
                <div class="form-group">
                        <!-- Campo nombre del formulario -->
                    <label for="tdn_name">Nombre <span class="asterisk">*</span></label>
                    <input type="text" id="tdn_name" class="form-control" name="tdn_name"
                        value="<?php echo $tdn_name; ?>" placeholder="Introduce tu nombre" required aria-required="true">
                    <?php    
                    // Comprobacion de errores de haberlos
                    if( is_wp_error( $reg_errors )) {
                        if( $reg_errors->get_error_message( "empty-name")) {
                            ?>
                            <br class="clearfix" />
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;
                                </button>
                                <p><?php echo $reg_errors->get_error_message( "empty-name" ); ?></p>
                            </div>
                        <?php
                        }
                    }
                    ?>
                </div>

                <div class="form-group">
                        <!-- Campo nombre del email -->
                    <label for="tdn_email">E-mail <span class="asterisk">*</span></label>
                    <input type="email" id="tdn_email" class="form-control" name="tdn_email"
                        value="<?php echo $tdn_email; ?>" placeholder="Introduce tu e-mail" required aria-required="true">
                    <?php    // Comprobacion de errores en la validacion
                    if( is_wp_error( $reg_errors )) {
                        if( $reg_errors->get_error_message( "empty-email")) {
                            ?>
                            <br class="clearfix" />
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;
                                </button>
                                <p><?php echo $reg_errors->get_error_message( "empty-email" ); ?></p>
                            </div>
                        <?php
                        }
                    }
                            // Comprobacion de errores en el formato
                    if( is_wp_error( $reg_errors )) {
                        if( $reg_errors->get_error_message( "invalid-email" )) {
                        ?>
                        <br class="clearfix" />
                        <div class="alert alert-warning alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert"
                                aria-hidden="true">
                                &times;
                            </button>
                            <p><?php echo $reg_errors->get_error_message( "invalid-email"); ?></p>
                        </div>
                        <?php
                        }
                    } 
                    ?>       
                </div>

                <div class="form-group">
                        <!-- Campo nombre del email -->
                    <label for="tdn_message">Mensaje <span class="asterisk">*</span></label>
                    <textarea id="tdn_message" class="form-control" name="tdn_message" rows="7"
                            placeholder="Introduce tu Mensaje" required aria-required="true">
                            <?php echo $tdn_message; ?>
                    </textarea>
                    <?php    // Comprobacion de errores en la validacion
                    if( is_wp_error( $reg_errors )) {
                        if( $reg_errors->get_error_message( "empty-message")) {
                            ?>
                            <br class="clearfix" />
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">
                                    &times;
                                </button>
                                <p><?php echo $reg_errors->get_error_message( "empty-message" ); ?></p>
                            </div>
                        <?php
                        }
                    }
                    ?>
                </div>

                <button type="submit" id="btn-submit" name="btn-submit" class="btn btn-default">
                    Enviar
                </button>
            </form>
        </article>
    </div>
</div>

</div>

