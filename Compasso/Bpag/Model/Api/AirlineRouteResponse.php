<?php
/**
 * AirlineRouteResponse
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
 * AirlineRouteResponse Class Doc Comment
 *
 * @category Class
 * @package  Compasso\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class AirlineRouteResponse implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'AirlineRouteResponse';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'arrival_airport' => 'string',
        'arrival_time' => '\DateTime',
        'carrier_code' => 'string',
        'departure_airport' => 'string',
        'departure_time' => '\DateTime',
        'fare_basis' => 'string',
        'flight_number' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'arrival_airport' => null,
        'arrival_time' => 'date-time',
        'carrier_code' => null,
        'departure_airport' => null,
        'departure_time' => 'date-time',
        'fare_basis' => null,
        'flight_number' => null
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
        'arrival_airport' => 'arrivalAirport',
        'arrival_time' => 'arrivalTime',
        'carrier_code' => 'carrierCode',
        'departure_airport' => 'departureAirport',
        'departure_time' => 'departureTime',
        'fare_basis' => 'fareBasis',
        'flight_number' => 'flightNumber'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'arrival_airport' => 'setArrivalAirport',
        'arrival_time' => 'setArrivalTime',
        'carrier_code' => 'setCarrierCode',
        'departure_airport' => 'setDepartureAirport',
        'departure_time' => 'setDepartureTime',
        'fare_basis' => 'setFareBasis',
        'flight_number' => 'setFlightNumber'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'arrival_airport' => 'getArrivalAirport',
        'arrival_time' => 'getArrivalTime',
        'carrier_code' => 'getCarrierCode',
        'departure_airport' => 'getDepartureAirport',
        'departure_time' => 'getDepartureTime',
        'fare_basis' => 'getFareBasis',
        'flight_number' => 'getFlightNumber'
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
        $this->container['arrival_airport'] = isset($data['arrival_airport']) ? $data['arrival_airport'] : null;
        $this->container['arrival_time'] = isset($data['arrival_time']) ? $data['arrival_time'] : null;
        $this->container['carrier_code'] = isset($data['carrier_code']) ? $data['carrier_code'] : null;
        $this->container['departure_airport'] = isset($data['departure_airport']) ? $data['departure_airport'] : null;
        $this->container['departure_time'] = isset($data['departure_time']) ? $data['departure_time'] : null;
        $this->container['fare_basis'] = isset($data['fare_basis']) ? $data['fare_basis'] : null;
        $this->container['flight_number'] = isset($data['flight_number']) ? $data['flight_number'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

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
     * Gets arrival_airport
     *
     * @return string
     */
    public function getArrivalAirport()
    {
        return $this->container['arrival_airport'];
    }

    /**
     * Sets arrival_airport
     *
     * @param string $arrival_airport Aeroporto de destino
     *
     * @return $this
     */
    public function setArrivalAirport($arrival_airport)
    {
        $this->container['arrival_airport'] = $arrival_airport;

        return $this;
    }

    /**
     * Gets arrival_time
     *
     * @return \DateTime
     */
    public function getArrivalTime()
    {
        return $this->container['arrival_time'];
    }

    /**
     * Sets arrival_time
     *
     * @param \DateTime $arrival_time Data da chegada ao destino
     *
     * @return $this
     */
    public function setArrivalTime($arrival_time)
    {
        $this->container['arrival_time'] = $arrival_time;

        return $this;
    }

    /**
     * Gets carrier_code
     *
     * @return string
     */
    public function getCarrierCode()
    {
        return $this->container['carrier_code'];
    }

    /**
     * Sets carrier_code
     *
     * @param string $carrier_code Código da compahia aérea
     *
     * @return $this
     */
    public function setCarrierCode($carrier_code)
    {
        $this->container['carrier_code'] = $carrier_code;

        return $this;
    }

    /**
     * Gets departure_airport
     *
     * @return string
     */
    public function getDepartureAirport()
    {
        return $this->container['departure_airport'];
    }

    /**
     * Sets departure_airport
     *
     * @param string $departure_airport Aeroporto de partida
     *
     * @return $this
     */
    public function setDepartureAirport($departure_airport)
    {
        $this->container['departure_airport'] = $departure_airport;

        return $this;
    }

    /**
     * Gets departure_time
     *
     * @return \DateTime
     */
    public function getDepartureTime()
    {
        return $this->container['departure_time'];
    }

    /**
     * Sets departure_time
     *
     * @param \DateTime $departure_time Data da partida
     *
     * @return $this
     */
    public function setDepartureTime($departure_time)
    {
        $this->container['departure_time'] = $departure_time;

        return $this;
    }

    /**
     * Gets fare_basis
     *
     * @return string
     */
    public function getFareBasis()
    {
        return $this->container['fare_basis'];
    }

    /**
     * Sets fare_basis
     *
     * @param string $fare_basis Fare Basis
     *
     * @return $this
     */
    public function setFareBasis($fare_basis)
    {
        $this->container['fare_basis'] = $fare_basis;

        return $this;
    }

    /**
     * Gets flight_number
     *
     * @return string
     */
    public function getFlightNumber()
    {
        return $this->container['flight_number'];
    }

    /**
     * Sets flight_number
     *
     * @param string $flight_number Número no voo
     *
     * @return $this
     */
    public function setFlightNumber($flight_number)
    {
        $this->container['flight_number'] = $flight_number;

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


