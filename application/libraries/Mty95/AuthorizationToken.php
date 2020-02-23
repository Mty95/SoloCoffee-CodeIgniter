<?php
namespace App\Library\Mty95;

use \Firebase\JWT\JWT;

class AuthorizationToken
{
    /**
     * Token Key
     */
    protected $token_key;

    /**
     * Token algorithm
     */
    protected $token_algorithm;

    /**
     * Request Header Name
     */
    protected $token_header = ['authorization','Authorization'];

    /**
     * Token Expire Time
     * ----------------------
     * ( 1 Day ) : 60 * 60 * 24 = 86400
     * ( 1 Hour ) : 60 * 60     = 3600
     */
    protected $token_expire_time = 86400;


    public function __construct()
    {
        $this->CI =& get_instance();

        /**
         * jwt config file load
         */
        $this->CI->load->config('jwt');

        /**
         * Load Config Items Values
         */
        $this->token_key        = $this->CI->config->item('jwt_key');
        $this->token_algorithm  = $this->CI->config->item('jwt_algorithm');
    }

    /**
     * Generate Token
     * @param: user data
     * @return string
     */
    public function generateToken($data)
    {
        try {
            return JWT::encode($data, $this->token_key, $this->token_algorithm);
        }
        catch(\Exception $e) {
            return 'Message: ' .$e->getMessage();
        }
    }

    /**
     * Validate Token with Header
     * Returns user information
     */
    public function validateToken()
    {
        /**
         * Request All Headers
         */
        $headers = $this->CI->input->request_headers();

        /**
         * Authorization Header Exists
         */
        $token_data = $this->tokenIsExist($headers);
        if($token_data['status'] === TRUE)
        {
            try
            {
                /**
                 * Token Decode
                 */
                try {
                    $token_decode = JWT::decode($headers[$token_data['key']], $this->token_key, array($this->token_algorithm));
                }
                catch(\Exception $e) {
                    return ['status' => FALSE, 'message' => $e->getMessage()];
                }

                if(!empty($token_decode) AND is_object($token_decode))
                {
                    // Check User ID (exists and numeric)
                    if(empty($token_decode->id) OR !is_numeric($token_decode->id))
                    {
                        return ['status' => FALSE, 'message' => 'User ID Not Define!'];

                        // Check Token Time
                    }else if(empty($token_decode->time OR !is_numeric($token_decode->time))) {

                        return ['status' => FALSE, 'message' => 'Token Time Not Define!'];
                    }
                    else
                    {
                        /**
                         * Check Token Time Valid
                         */
                        $time_difference = strtotime('now') - $token_decode->time;
                        if( $time_difference >= $this->token_expire_time )
                        {
                            return ['status' => FALSE, 'message' => 'Token Time Expire.'];

                        }else
                        {
                            /**
                             * All Validation False Return Data
                             */
                            return ['status' => TRUE, 'data' => $token_decode];
                        }
                    }

                }else{
                    return ['status' => FALSE, 'message' => 'Forbidden'];
                }
            }
            catch(\Exception $e) {
                return ['status' => FALSE, 'message' => $e->getMessage()];
            }
        }else
        {
            // Authorization Header Not Found!
            return ['status' => FALSE, 'message' => $token_data['message'] ];
        }
    }

    /**
     * Validate Token with POST Request
     */
    public function validateTokenPost()
    {
        if(isset($_POST['token']))
        {
            $token = $this->CI->input->post('token', TRUE);
            if(!empty($token) AND is_string($token) AND !is_array($token))
            {
                try
                {
                    /**
                     * Token Decode
                     */
                    try {
                        $token_decode = JWT::decode($token, $this->token_key, array($this->token_algorithm));
                    }
                    catch(\Exception $e) {
                        return ['status' => FALSE, 'message' => $e->getMessage()];
                    }

                    if(!empty($token_decode) AND is_object($token_decode))
                    {
                        // Check User ID (exists and numeric)
                        if(empty($token_decode->id) OR !is_numeric($token_decode->id))
                        {
                            return ['status' => FALSE, 'message' => 'User ID Not Define!'];

                            // Check Token Time
                        }else if(empty($token_decode->time OR !is_numeric($token_decode->time))) {

                            return ['status' => FALSE, 'message' => 'Token Time Not Define!'];
                        }
                        else
                        {
                            /**
                             * Check Token Time Valid
                             */
                            $time_difference = strtotime('now') - $token_decode->time;
                            if( $time_difference >= $this->token_expire_time )
                            {
                                return ['status' => FALSE, 'message' => 'Token Time Expire.'];

                            }else
                            {
                                /**
                                 * All Validation False Return Data
                                 */
                                return ['status' => TRUE, 'data' => $token_decode];
                            }
                        }

                    }else{
                        return ['status' => FALSE, 'message' => 'Forbidden'];
                    }
                }
                catch(\Exception $e) {
                    return ['status' => FALSE, 'message' => $e->getMessage()];
                }
            }else
            {
                return ['status' => FALSE, 'message' => 'Token is not defined.' ];
            }
        } else {
            return ['status' => FALSE, 'message' => 'Token is not defined.'];
        }
    }

    /**
     * Token Header Check
     * @param: request headers
     * @return array
     */
    public function tokenIsExist($headers)
    {
        if(!empty($headers) AND is_array($headers)) {
            foreach ($this->token_header as $key) {
                if (array_key_exists($key, $headers) AND !empty($key))
                    return ['status' => TRUE, 'key' => $key];
            }
        }
        return ['status' => FALSE, 'message' => 'Token is not defined.'];
    }

    /**
     * Fetch User Data
     * -----------------
     * @param: token
     * @return: user_data
     */
    public function userData()
    {
        /**
         * Request All Headers
         */
        $headers = $this->CI->input->request_headers();

        /**
         * Authorization Header Exists
         */
        $token_data = $this->tokenIsExist($headers);
        if($token_data['status'] === TRUE)
        {
            try
            {
                /**
                 * Token Decode
                 */
                try {
                    $token_decode = JWT::decode($headers[$token_data['key']], $this->token_key, array($this->token_algorithm));
                }
                catch(\Exception $e) {
                    return ['status' => FALSE, 'message' => $e->getMessage()];
                }

                if(!empty($token_decode) AND is_object($token_decode))
                {
                    return $token_decode;
                }else{
                    return ['status' => FALSE, 'message' => 'Forbidden'];
                }
            }
            catch(\Exception $e) {
                return ['status' => FALSE, 'message' => $e->getMessage()];
            }
        }else
        {
            // Authorization Header Not Found!
            return ['status' => FALSE, 'message' => $token_data['message'] ];
        }
    }

    /**
     * @since 2.1.0
     * @param int $id
     * @param array $data
     *
     * @return null|string
     */
    public function generateUserToken(int $id, array $data = []): ?string
    {
        $request['id'] = $id;

        foreach ($data as $key => $value)
        {
            if (in_array($key, ['id', 'time']))
            {
                continue;
            }

            $request[$key] = $value;
        }

        $request['time'] = time();

        try {
            return JWT::encode($request, $this->token_key, $this->token_algorithm);
        } catch (\Exception $e) {
            return null;
        }
    }
}