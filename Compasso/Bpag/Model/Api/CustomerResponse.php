<?php
/**
 * CustomerResponse
 *
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

namespace Compasso\Bpag\Model\Api;

use \ArrayAccess;
use Compasso\Bpag\Model\Api\ObjectSerializer;

/**
 * CustomerResponse Class Doc Comment
 *
 * @category Class
 * @description Dados referentes ao cliente
 * @package  Compasso\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class CustomerResponse implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'CustomerResponse';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'addresses' => '\Compasso\Bpag\Model\Api\AddressResponse[]',
        'birthday' => '\DateTime',
        'document' => 'string',
        'document_type' => 'string',
        'email' => 'string',
        'first_name' => 'string',
        'gender' => 'string',
        'id' => 'string',
        'ip_address' => 'string',
        'last_name' => 'string',
        'marital_status' => 'string',
        'middle_name' => 'string',
        'phones' => '\Compasso\Bpag\Model\Api\PhoneResponse[]',
        'type' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'addresses' => null,
        'birthday' => 'date',
        'document' => null,
        'document_type' => null,
        'email' => null,
        'first_name' => null,
        'gender' => null,
        'id' => null,
        'ip_address' => null,
        'last_name' => null,
        'marital_status' => null,
        'middle_name' => null,
        'phones' => null,
        'type' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'addresses' => 'addresses',
        'birthday' => 'birthday',
        'document' => 'document',
        'document_type' => 'documentType',
        'email' => 'email',
        'first_name' => 'firstName',
        'gender' => 'gender',
        'id' => 'id',
        'ip_address' => 'ipAddress',
        'last_name' => 'lastName',
        'marital_status' => 'maritalStatus',
        'middle_name' => 'middleName',
        'phones' => 'phones',
        'type' => 'type'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'addresses' => 'setAddresses',
        'birthday' => 'setBirthday',
        'document' => 'setDocument',
        'document_type' => 'setDocumentType',
        'email' => 'setEmail',
        'first_name' => 'setFirstName',
        'gender' => 'setGender',
        'id' => 'setId',
        'ip_address' => 'setIpAddress',
        'last_name' => 'setLastName',
        'marital_status' => 'setMaritalStatus',
        'middle_name' => 'setMiddleName',
        'phones' => 'setPhones',
        'type' => 'setType'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'addresses' => 'getAddresses',
        'birthday' => 'getBirthday',
        'document' => 'getDocument',
        'document_type' => 'getDocumentType',
        'email' => 'getEmail',
        'first_name' => 'getFirstName',
        'gender' => 'getGender',
        'id' => 'getId',
        'ip_address' => 'getIpAddress',
        'last_name' => 'getLastName',
        'marital_status' => 'getMaritalStatus',
        'middle_name' => 'getMiddleName',
        'phones' => 'getPhones',
        'type' => 'getType'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    const DOCUMENT_TYPE_UNKNOWN = 'UNKNOWN';
    const DOCUMENT_TYPE_CPF = 'CPF';
    const DOCUMENT_TYPE_CNPJ = 'CNPJ';
    const DOCUMENT_TYPE_RG = 'RG';
    const DOCUMENT_TYPE_DRIVER_LICENSE = 'DRIVER_LICENSE';
    const DOCUMENT_TYPE_MILITARY_ID = 'MILITARY_ID';
    const DOCUMENT_TYPE_PASSPORT = 'PASSPORT';
    const DOCUMENT_TYPE_SSN = 'SSN';
    const DOCUMENT_TYPE_OTHERS = 'OTHERS';
    const GENDER_UNKNOWN = 'UNKNOWN';
    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';
    const MARITAL_STATUS_UNKNOWN = 'UNKNOWN';
    const MARITAL_STATUS_DIVORCED = 'DIVORCED';
    const MARITAL_STATUS_MARRIED = 'MARRIED';
    const MARITAL_STATUS_SINGLE = 'SINGLE';
    const MARITAL_STATUS_WIDOWED = 'WIDOWED';
    const TYPE_UNKNOWN = 'UNKNOWN';
    const TYPE_CUSTOMER = 'CUSTOMER';
    const TYPE_SHIPPING = 'SHIPPING';
    const TYPE_BILLING = 'BILLING';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getDocumentTypeAllowableValues()
    {
        return [
            self::DOCUMENT_TYPE_UNKNOWN,
            self::DOCUMENT_TYPE_CPF,
            self::DOCUMENT_TYPE_CNPJ,
            self::DOCUMENT_TYPE_RG,
            self::DOCUMENT_TYPE_DRIVER_LICENSE,
            self::DOCUMENT_TYPE_MILITARY_ID,
            self::DOCUMENT_TYPE_PASSPORT,
            self::DOCUMENT_TYPE_SSN,
            self::DOCUMENT_TYPE_OTHERS,
        ];
    }
    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getGenderAllowableValues()
    {
        return [
            self::GENDER_UNKNOWN,
            self::GENDER_MALE,
            self::GENDER_FEMALE,
        ];
    }
    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getMaritalStatusAllowableValues()
    {
        return [
            self::MARITAL_STATUS_UNKNOWN,
            self::MARITAL_STATUS_DIVORCED,
            self::MARITAL_STATUS_MARRIED,
            self::MARITAL_STATUS_SINGLE,
            self::MARITAL_STATUS_WIDOWED,
        ];
    }
    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getTypeAllowableValues()
    {
        return [
            self::TYPE_UNKNOWN,
            self::TYPE_CUSTOMER,
            self::TYPE_SHIPPING,
            self::TYPE_BILLING,
        ];
    }
    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['addresses'] = isset($data['addresses']) ? $data['addresses'] : null;
        $this->container['birthday'] = isset($data['birthday']) ? $data['birthday'] : null;
        $this->container['document'] = isset($data['document']) ? $data['document'] : null;
        $this->container['document_type'] = isset($data['document_type']) ? $data['document_type'] : null;
        $this->container['email'] = isset($data['email']) ? $data['email'] : null;
        $this->container['first_name'] = isset($data['first_name']) ? $data['first_name'] : null;
        $this->container['gender'] = isset($data['gender']) ? $data['gender'] : null;
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['ip_address'] = isset($data['ip_address']) ? $data['ip_address'] : null;
        $this->container['last_name'] = isset($data['last_name']) ? $data['last_name'] : null;
        $this->container['marital_status'] = isset($data['marital_status']) ? $data['marital_status'] : null;
        $this->container['middle_name'] = isset($data['middle_name']) ? $data['middle_name'] : null;
        $this->container['phones'] = isset($data['phones']) ? $data['phones'] : null;
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        $allowedValues = $this->getDocumentTypeAllowableValues();
        if (!is_null($this->container['document_type']) && !in_array($this->container['document_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'document_type', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getGenderAllowableValues();
        if (!is_null($this->container['gender']) && !in_array($this->container['gender'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'gender', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getMaritalStatusAllowableValues();
        if (!is_null($this->container['marital_status']) && !in_array($this->container['marital_status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'marital_status', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getTypeAllowableValues();
        if (!is_null($this->container['type']) && !in_array($this->container['type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'type', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets addresses
     *
     * @return \Compasso\Bpag\Model\Api\AddressResponse[]
     */
    public function getAddresses()
    {
        return $this->container['addresses'];
    }

    /**
     * Sets addresses
     *
     * @param \Compasso\Bpag\Model\Api\AddressResponse[] $addresses Endereços
     *
     * @return $this
     */
    public function setAddresses($addresses)
    {
        $this->container['addresses'] = $addresses;

        return $this;
    }

    /**
     * Gets birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->container['birthday'];
    }

    /**
     * Sets birthday
     *
     * @param \DateTime $birthday Data de nascimento
     *
     * @return $this
     */
    public function setBirthday($birthday)
    {
        $this->container['birthday'] = $birthday;

        return $this;
    }

    /**
     * Gets document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->container['document'];
    }

    /**
     * Sets document
     *
     * @param string $document Número do documento
     *
     * @return $this
     */
    public function setDocument($document)
    {
        $this->container['document'] = $document;

        return $this;
    }

    /**
     * Gets document_type
     *
     * @return string
     */
    public function getDocumentType()
    {
        return $this->container['document_type'];
    }

    /**
     * Sets document_type
     *
     * @param string $document_type Tipo de documento
     *
     * @return $this
     */
    public function setDocumentType($document_type)
    {
        $allowedValues = $this->getDocumentTypeAllowableValues();
        if (!is_null($document_type) && !in_array($document_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'document_type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['document_type'] = $document_type;

        return $this;
    }

    /**
     * Gets email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->container['email'];
    }

    /**
     * Sets email
     *
     * @param string $email E-mail
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->container['email'] = $email;

        return $this;
    }

    /**
     * Gets first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->container['first_name'];
    }

    /**
     * Sets first_name
     *
     * @param string $first_name Primeiro nome
     *
     * @return $this
     */
    public function setFirstName($first_name)
    {
        $this->container['first_name'] = $first_name;

        return $this;
    }

    /**
     * Gets gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->container['gender'];
    }

    /**
     * Sets gender
     *
     * @param string $gender Sexo
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $allowedValues = $this->getGenderAllowableValues();
        if (!is_null($gender) && !in_array($gender, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'gender', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['gender'] = $gender;

        return $this;
    }

    /**
     * Gets id
     *
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     *
     * @param string $id Identificador do cliente no sistema do lojista
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets ip_address
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->container['ip_address'];
    }

    /**
     * Sets ip_address
     *
     * @param string $ip_address Endereço de IP
     *
     * @return $this
     */
    public function setIpAddress($ip_address)
    {
        $this->container['ip_address'] = $ip_address;

        return $this;
    }

    /**
     * Gets last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->container['last_name'];
    }

    /**
     * Sets last_name
     *
     * @param string $last_name ùltimo nome
     *
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->container['last_name'] = $last_name;

        return $this;
    }

    /**
     * Gets marital_status
     *
     * @return string
     */
    public function getMaritalStatus()
    {
        return $this->container['marital_status'];
    }

    /**
     * Sets marital_status
     *
     * @param string $marital_status Estado civil
     *
     * @return $this
     */
    public function setMaritalStatus($marital_status)
    {
        $allowedValues = $this->getMaritalStatusAllowableValues();
        if (!is_null($marital_status) && !in_array($marital_status, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'marital_status', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['marital_status'] = $marital_status;

        return $this;
    }

    /**
     * Gets middle_name
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->container['middle_name'];
    }

    /**
     * Sets middle_name
     *
     * @param string $middle_name Nome do meio
     *
     * @return $this
     */
    public function setMiddleName($middle_name)
    {
        $this->container['middle_name'] = $middle_name;

        return $this;
    }

    /**
     * Gets phones
     *
     * @return \Compasso\Bpag\Model\Api\PhoneResponse[]
     */
    public function getPhones()
    {
        return $this->container['phones'];
    }

    /**
     * Sets phones
     *
     * @param \Compasso\Bpag\Model\Api\PhoneResponse[] $phones Telefones
     *
     * @return $this
     */
    public function setPhones($phones)
    {
        $this->container['phones'] = $phones;

        return $this;
    }

    /**
     * Gets type
     *
     * @return string
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param string $type Tipo de cliente
     *
     * @return $this
     */
    public function setType($type)
    {
        $allowedValues = $this->getTypeAllowableValues();
        if (!is_null($type) && !in_array($type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['type'] = $type;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


