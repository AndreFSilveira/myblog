<?php
/*
Plugin Name: Widget cadastroNews
Description: box de Cadastro da newsletter
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class cadastroNews extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_cadastroNews_widget','cadastroNews', array('description' => 'box de Cadastro da newsletter'));
	}
	public function widget($args, $instance){ ?>
      <div class="vitrine-cadastro">
                <?php $sucess_message = get_message_success_newsletter(); ?>
                <?php if (!empty($sucess_message)): ?>
                    <div class="sucesso-cadastro">
                        <ul>
                            <li><?php echo $sucess_message[0]; ?> </li>
                        </ul>
                    </div>
                <?php endif; ?>
                <form action="" method="POST" style="display:<?php echo (!empty($sucess_message)) ? 'none': 'block' ?>">
                    <?php $mensagens = get_message_error_newsletter(); ?>
                    <?php if (is_array($mensagens)): ?>
                            <div class="erro-cadastro">
                            <ul>
                                <?php foreach ($mensagens as $item): ?>
                                <li><?php echo $item; ?></li>
                                <?php endforeach; ?>
                            </ul>
                            </div>
                    <?php endif; ?>
                    <p class="p-sub-01">CADASTRO VIP</p>
                    <p class="p-sub-02">Preencha os campos abaixo e receba nosso catálogo virtual por e-mail.</p>
                    <input type="hidden" name="cadastronews" value"1">
                    <input type="text"
                            id="vitrine_cadastro_txt"
                            name="vitrine_cadastro_txt"
                            title="Insira aqui o seu nome"
                            value="<?php echo (isset($_POST['vitrine_cadastro_txt']) ? $_POST['vitrine_cadastro_txt'] : 'Insira aqui o seu nome');?>" 
                    />
                    <input type="text"
                            id="vitrine_cadastro_email"
                            name="vitrine_cadastro_email"
                            title="Insira aqui o seu email"
                            value="<?php echo (isset($_POST['vitrine_cadastro_email']) ? $_POST['vitrine_cadastro_email'] : 'Insira aqui o seu email');?>" 
                    />
                    <input type="submit" id="vitrine_cadastro_btn" name="vitrine_cadastro_btn" title="OK" value="OK" />
                    <div class="clear"></div>
                </form>
            </div>
        <?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "cadastroNews" );' ) );
