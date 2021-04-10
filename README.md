### Please Read this to know API endpoints

#### Steps To Install and Run

**Prerequisits**
1. php 7.x or above
2. composer

**Installation**
1. Run composer install

**Running Project**
1. Run php -S 127.0.0.1:8000 -t public

**Structure while hitting API**
1. http://{domain_name}:{port_no}/api/{api-name}
**Eg.** http://127.0.0.1:8000/api/get-average-posts-per-user-per-month


**APIs**

1. **Get Average character length of posts per month**
    * Method name - get-average-length-of-post-per-month
    * Description - Returns Average Length of posts per month
    * Type - GET
    * Request Structure -
        - sl_token: 
            - type: string
            - description: sl_token provided by supermetrics register API
        - page: 
            - type: int
            - description: page number of which data needs to be fetched
    * Response Structure -
        - status_code_header: 
            - type: string
            - description: HTTP Response header with custom message
        - body:
            - type: object
            - description: which contains data

            data:
                type: Map
                description:
                    Key:
                        type: string
                        description: Year
                    Value:
                        type: Map
                        description:
                            Key:
                                type: string
                                description: Month
                            Value:
                                type: int
                                description: Average of posts length per month
    
    * Response Example: 
        ```javascript
        {
            "status_code_header": "HTTP/1.1 200 Success",
            "body": {
                "data": {
                    "2021": {
                        "Mar": 391
                    }
                }
            }
        }
        ```
