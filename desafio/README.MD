- Foi utilizado o WAMP 3.4.1 em ambiente Windows 10
- Verificar se o arquivo de configurações "httpd.conf" do Apache Tom Cat utilizado pelo WAMP já encontra-se configurado com a seguinte linha:
    * LoadModule rewrite_module modules/mod_rewrite.so
- Configurações utilizadas pelo banco de dados MySQL:
    * 'root'
    * ''
- Base de dados criada no MySQL:
    * desafio
- Copiar o conteudo do projeto desafio para "%WAMP_HOME%/www/desafio/"
- Copiar o conteudo do projeto desafio-front para "%WAMP_HOME%/www/desafio-front/"
- Executar no banco de Dados MySQL e na instancia mencionada acima, o script que encontra-se no caminho:
    * desafio/database/desafio.sql
- Refresh do servidor WAMP
- Executar exclusivamente o browser Google Chrome e acessar a URL a seguir para confirmar a execução do projeto "desafio":
    * http://localhost/desafio/v1/product
    * Espera-se o JSON: 
        * {"products":[{"id":1,"name":"Produto 01","price":120},{"id":2,"name":"Produto 02","price":89.9},{"id":3,"name":"Produto 03","price":19.99}],"total":3}
- Executar exclusivamente o browser Google Chrome e acessar a aplicação em:
    * http://localhost/desafio-front/index.html