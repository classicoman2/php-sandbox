<?php

/* login.html.twig */
class __TwigTemplate_5645a7b61d232b726c1349f37405832c48191aca6760cfbd9dad17152ca0e127 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" dir=\"ltr\" lang=\"en-US\">
    <head>
        <title>Login al sistema Fenix - invAppX</title>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"css/login.css\"/>
    </head>
    <body>
        <div id=\"login-container\">
            <div id=\"login-top\">
            </div>
            <div id=\"login-sub-header\">
                User Login
            </div>
            <div id=\"login-sub\">
                <form id=\"login-form\" name=\"userCheck\" method=\"post\" action=\"controllers/logincheck.php\">
                    <div>
                        <div class=\"label\"> Username </div>
                        <div class=\"input\">
                            <input name=\"myusername\" type=\"text\" id=\"myusername\"/>
                        </div>
                    </div>
                    <div>
                        <div class=\"label\"> Password </div>
                        <div class=\"input\">
                            <input name=\"mypassword\" type=\"password\" id=\"mypassword\"/>
                        </div>
                    </div>
\t\t\t<!-- Com posar un camp dentrada tipo contrasenya
\t\t\thttp://www.lawebdelprogramador.com/foros/HTML/88722input_type_con_asteriscos.html -->
                    <div id=\"login_button\">
                        <input type=\"submit\" name=\"Submit\" value=\"Login\"/>
                    </div>
                </form>
            </div>
        </div>
        <div id=\"footer\">
            <p>EASDIB - 2014
            <!--    <a href=\"http://validator.w3.org/check?uri=referer\">
                    <img src=\"http://www.w3.org/Icons/valid-xhtml10\" alt=\"Valid XHTML 1.0 Transitional\" height=\"31\" width=\"88\" />
                </a>-->
            </p>
        </div>
    </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "login.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
