<?php
/**
 * Configuration
 * PHP version 5
 *
 * @category Class
 * @package  Compasso\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * API BPAG Gateway de Pagamentos
 *
 * UOL Diveo Payment PCI Gateway  # Authenticação  Para autenticação com a plataforma de pagamentos o sistema cliente deve se encarregar do envio dos seguintes headers    Header  | Obrigatório | Descrição | Exemplo  --------|-------------|---------|----------  Merchant | Sim | Empresa fornecida pelo UOL Diveo | uoldiveo  Account | Sim | Unidade fornecida pelo UOL Diveo | uoldiveo  Date | Sim | Data atual no formato `EEE, d MMM yyyy HH:mm:ss z` | Wed, 25 Jun 2018 12:00:00 GMT  Authorization | Sim | Chave calculada com base na requisição | UOLWS access-id:signature:hmac-algorithm:protocol-version  OnBehalfOfAccessId | Não | Em caso de utilização de Access Id/Client Secret diferente ao do Merchant, deve ser enviado o Access Id do Merchant | e401cf78cc8d85fd92f1adda74a4c160    ## Descrição do Protocolo da ApiSecurity  Para todo cliente de um web service deve ser criada sua respectiva credencial.  As credenciais são formadas por access-id e secret-key.  O access-id é um identificador único e aleatório de um cliente(exemplo: 99d1932acad7a4ce93ea321495b96cde)  A secret-key é um valor aleatório criado de forma segura para melhor atender as necessidades criptograficas do algoritmo de assinatura (exemplo: G8ZnZ1EkSDPcsgBXLoAQeyhLyEfBXNSkezSqnW/akZQ=)    Essa credencial deverá ser inserida no Header Authorization de todas as requisições do cliente de forma a identificá-lo, como mostrado abaixo.    - **Authorization**: UOLWS access-id:signature:hmac-algorithm:protocol-version  - **signature**: explicado abaixo em 'Calculando a Assinatura'  - **hash-algorithm**: Identificador de um algoritmo de HMAC utilizado para assinar a requisição. As opções são 's1' para HMacSHA1 e 's2' para HMacSHA256. Cada web service pode escolher quais dos algoritmos ele suporta e, caso esse parâmetro seja omitido, qual é o algoritmo padrão. Outras opções podem ser definidas pelos webservices, caso necessário. deve ser usado apenas na versão 0.2 do protocolo.  - **protocol-version**: versão do protocolo de autenticação UOLWS. Opções são 0.1 ou 0.2.    ## Calculando a Assinatura  ### Protocolo Versão 0.2    - **signature**: Base64( hmac-algorithm( secret-key, string-to-sign) );  - **string-to-sign**: `HTTP-Verb + \"\\n\" + Content-MD5 + \"\\n\" + Content-Type + \"\\n\" + Date + \"\\n\" + Canonicalized-X-UOL-Headers + HTTP-Path-Info`  - **HTTP-Verb**: O verbo da requisição HTTP; POST, GET, DELETE etc.    > Os headers Content-MD5, Content-Type e Date tem natureza posicional, por isso, o nome do Header não deve ser inserido na StringToSign, apenas seus valores. Se os Headers Content-Type e Content-MD5 não estiverem presentes na requisição(ambos são opcionais para requisições PUT e sem sentido para GET) considerar a string vazia como o seu valor, ficando apenas o \"\\n\" na respectiva posição. O valor do Header Date é obrigatório.    - **Canonicalized-X-UOL-Headers**     1.  Converter os Headers que comecem com 'X-UOL-' para lower-case. Por exemplo, 'X-UOL-AlternateKey' se torna 'x-uol-alternatekey'     2. Ordenar os Headers lexicograficamente pelos seus nomes. Desse mode x-uol-alternatekey vem antes de x-uol-date.     3. Combinar os Header com o mesmo nome em um Header único com os seus valores separados por vírgulas e um espaços em branco entre os valores. Por exemplo, 'x-uol-alternate-key: ip=127.0.0.1' e 'x-uol-alternate-key: idt_person=1237654' devem ser combinados em 'x-uol-alternate-key: ip=127.0.0.1, idt_person=1237654'     4. Fazer 'Unfold' dos Headers longos, removendo LWS(rfc2616, sessão 2.2) por um único espaço em branco.     5. Remover os espaços entre os dois pontos e o valor do header. Por exemplo 'x-uol-alternate-key: ip=127.0.0.1,idt_person=1237654' se torna 'x-uol-alternate-key:ip=127.0.0.1,idt_person=1237654'     6. Finalmente, adicione um 'new-line'(\\n) para cada Header da lista, resultando em uma só String.    - **HTTP-Path-Info**: A parte de URL da requisição que vai de após nome do servidor até a query string. Exemplos    Primeira Linha da requisição  | HTTP-Path-Info  --------|-------------  GET http://foo.bar/a.html | /a.html  HEAD /xyz?a=b HTTP/1.1 | /xyz  POST /some/path.html HTTP/1.1 | /some/path.html    ### Protocolo Versão 0.1    > Devido a questões de segurança este protocolo foi descontinuado
 *
 * OpenAPI spec version: 3.0.X
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 2.4.14
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Compasso\Client;

/**
 * Configuration Class Doc Comment
 * PHP version 5
 *
 * @category Class
 * @package  Compasso\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class Configuration
{
    private static $defaultConfiguration;

    /**
     * Associate array to store API key(s)
     *
     * @var string[]
     */
    protected $apiKeys = [];

    /**
     * Associate array to store API prefix (e.g. Bearer)
     *
     * @var string[]
     */
    protected $apiKeyPrefixes = [];

    /**
     * Access token for OAuth
     *
     * @var string
     */
    protected $accessToken = '';

    /**
     * Username for HTTP basic authentication
     *
     * @var string
     */
    protected $username = '';

    /**
     * Password for HTTP basic authentication
     *
     * @var string
     */
    protected $password = '';

    /**
     * The host
     *
     * @var string
     */
    protected $host = 'https://127.0.0.1:11000/upbc-service-fe';

    /**
     * User agent of the HTTP request, set to "PHP-Swagger" by default
     *
     * @var string
     */
    protected $userAgent = 'Swagger-Codegen/1.0.0/php';

    /**
     * Debug switch (default set to false)
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * Debug file location (log to STDOUT by default)
     *
     * @var string
     */
    protected $debugFile = 'php://output';

    /**
     * Debug file location (log to STDOUT by default)
     *
     * @var string
     */
    protected $tempFolderPath;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tempFolderPath = sys_get_temp_dir();
    }

    /**
     * Sets API key
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     * @param string $key              API key or token
     *
     * @return $this
     */
    public function setApiKey($apiKeyIdentifier, $key)
    {
        $this->apiKeys[$apiKeyIdentifier] = $key;
        return $this;
    }

    /**
     * Gets API key
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     *
     * @return string API key or token
     */
    public function getApiKey($apiKeyIdentifier)
    {
        return isset($this->apiKeys[$apiKeyIdentifier]) ? $this->apiKeys[$apiKeyIdentifier] : null;
    }

    /**
     * Sets the prefix for API key (e.g. Bearer)
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     * @param string $prefix           API key prefix, e.g. Bearer
     *
     * @return $this
     */
    public function setApiKeyPrefix($apiKeyIdentifier, $prefix)
    {
        $this->apiKeyPrefixes[$apiKeyIdentifier] = $prefix;
        return $this;
    }

    /**
     * Gets API key prefix
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     *
     * @return string
     */
    public function getApiKeyPrefix($apiKeyIdentifier)
    {
        return isset($this->apiKeyPrefixes[$apiKeyIdentifier]) ? $this->apiKeyPrefixes[$apiKeyIdentifier] : null;
    }

    /**
     * Sets the access token for OAuth
     *
     * @param string $accessToken Token for OAuth
     *
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Gets the access token for OAuth
     *
     * @return string Access token for OAuth
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Sets the username for HTTP basic authentication
     *
     * @param string $username Username for HTTP basic authentication
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Gets the username for HTTP basic authentication
     *
     * @return string Username for HTTP basic authentication
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the password for HTTP basic authentication
     *
     * @param string $password Password for HTTP basic authentication
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Gets the password for HTTP basic authentication
     *
     * @return string Password for HTTP basic authentication
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the host
     *
     * @param string $host Host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Gets the host
     *
     * @return string Host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Sets the user agent of the api client
     *
     * @param string $userAgent the user agent of the api client
     *
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        if (!is_string($userAgent)) {
            throw new \InvalidArgumentException('User-agent must be a string.');
        }

        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Gets the user agent of the api client
     *
     * @return string user agent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Sets debug flag
     *
     * @param bool $debug Debug flag
     *
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * Gets the debug flag
     *
     * @return bool
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Sets the debug file
     *
     * @param string $debugFile Debug file
     *
     * @return $this
     */
    public function setDebugFile($debugFile)
    {
        $this->debugFile = $debugFile;
        return $this;
    }

    /**
     * Gets the debug file
     *
     * @return string
     */
    public function getDebugFile()
    {
        return $this->debugFile;
    }

    /**
     * Sets the temp folder path
     *
     * @param string $tempFolderPath Temp folder path
     *
     * @return $this
     */
    public function setTempFolderPath($tempFolderPath)
    {
        $this->tempFolderPath = $tempFolderPath;
        return $this;
    }

    /**
     * Gets the temp folder path
     *
     * @return string Temp folder path
     */
    public function getTempFolderPath()
    {
        return $this->tempFolderPath;
    }

    /**
     * Gets the default configuration instance
     *
     * @return Configuration
     */
    public static function getDefaultConfiguration()
    {
        if (self::$defaultConfiguration === null) {
            self::$defaultConfiguration = new Configuration();
        }

        return self::$defaultConfiguration;
    }

    /**
     * Sets the detault configuration instance
     *
     * @param Configuration $config An instance of the Configuration Object
     *
     * @return void
     */
    public static function setDefaultConfiguration(Configuration $config)
    {
        self::$defaultConfiguration = $config;
    }

    /**
     * Gets the essential information for debugging
     *
     * @return string The report for debugging
     */
    public static function toDebugReport()
    {
        $report  = 'PHP SDK (Compasso\Client) Debug Report:' . PHP_EOL;
        $report .= '    OS: ' . php_uname() . PHP_EOL;
        $report .= '    PHP Version: ' . PHP_VERSION . PHP_EOL;
        $report .= '    OpenAPI Spec Version: 3.0.X' . PHP_EOL;
        $report .= '    Temp Folder Path: ' . self::getDefaultConfiguration()->getTempFolderPath() . PHP_EOL;

        return $report;
    }

    /**
     * Get API key (with prefix if set)
     *
     * @param  string $apiKeyIdentifier name of apikey
     *
     * @return string API key with the prefix
     */
    public function getApiKeyWithPrefix($apiKeyIdentifier)
    {
        $prefix = $this->getApiKeyPrefix($apiKeyIdentifier);
        $apiKey = $this->getApiKey($apiKeyIdentifier);

        if ($apiKey === null) {
            return null;
        }

        if ($prefix === null) {
            $keyWithPrefix = $apiKey;
        } else {
            $keyWithPrefix = $prefix . ' ' . $apiKey;
        }

        return $keyWithPrefix;
    }
}
