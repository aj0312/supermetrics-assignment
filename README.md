### Please Read this to know API endpoints

#### Steps To Install and Run

**Prerequisits**
1. php 7.x or above
2. composer

**Libraries used**
1. vlucas/phpdotenv
    * Purpose - to fetch data from .env file
2. psr-4
    * Purpose - to avoid include and require statements in code and instead autoload files

**Installation**
1. Run composer install

**Running Project**
1. Run php -S 127.0.0.1:8000 -t public
2. Get sl_token from Supermetrics register API
3. Apply the sl_token received while hitting any API to the parameter

**Note: if you get invalid sl_token message from an API, then re-register**

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
        ```
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
        ```
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

2. **Longest post by character length per month**
    * Method name - get-longest-post-per-month
    * Description - Returns Longest post by character per month
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
        ```
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
                                type: object
                                description: post object with character length of the post
        ```
    * Response Example: 
        ```javascript
        {
            "status_code_header": "HTTP/1.1 200 Success",
            "body": {
                "data": {
                    "2021": {
                        "Feb": {
                            "id": "abc",
                            "from_name": "xyz",
                            "from_id": "da321",
                            "message": "Test Message",
                            "type": "New",
                            "created_time": "2020-04-01T15:22:04+00:00",
                            "char_length": 765
                        }
                    }
                }
            }
        }
        ```

3. **Total posts split by week number**
    * Method name - get-total-posts-per-week
    * Description - Returns posts per week
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
        ```
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
                                description: Week Number
                            Value:
                                type: int
                                description: Total posts in week
        ```
    * Response Example: 
        ```javascript
        {
            "status_code_header": "HTTP/1.1 200 Success",
            "body": {
                "data": {
                    "2021": {
                        "06": 20,
                        "05": 21,
                        "04": 44,
                        "03": 1
                    }
                }
            }
        }
        ```

4. **Average number of posts per user per month**
    * Method name - get-average-posts-per-user-per-month
    * Description - Returns average posts per user per month
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
        ```
            data:
                type: Map
                description:
                    Key:
                        type: string
                        description: from_id/user id
                    Value:
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
                                        type: float
                                        description: Average count of post in month for user
        ```
    * Response Example: 
        ```javascript
        {
            "status_code_header": "HTTP/1.1 200 Success",
            "body": {
                "data": {
                    "user_19": {
                        "2021": {
                            "Feb": 0.5,
                            "Jan": 0.5
                        }
                    }
                }
            }
        }
        ```
