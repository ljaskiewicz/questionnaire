# Questionnaire case study

The repository includes a questionnaire mechanism with supplementary questions, the completion of which leads to product suggestions based on the choices made.

1. Clone repository
2. run `make build-dev`

3. call GET `http://localhost:8181/questionnaire/94cf1c49-b747-4a1b-a03c-c63f4110a54a` to see questionnaire

4. call POST `http://localhost:8181/questionnaire` to process user answers. below example payload

```
{
    "userId": "98f35cac-600c-45f1-b9f5-12418dd367ef",
    "questionnaireId": "94cf1c49-b747-4a1b-a03c-c63f4110a54a",
    "userAnswers": [
        {
            "questionId": "067ad43b-4a15-4a8d-b120-c20e8dd5e6c6",
            "answerId": "87e66701-1d47-4107-8e4c-1010e3a50b3c"
        },
        {
            "questionId": "30d65970-3510-41d7-b2d9-2a25d014ea30",
            "answerId": "3eb81ee1-7925-4db0-affc-9a7b805ea91a"
        },
        {
            "questionId": "49b9b281-6b44-4a78-862e-ba74fcb75d0d",
            "answerId": "d916d728-08cf-4f76-ac24-6826baecbde1"
        },
        {
            "questionId": "b96aa45a-b2a4-4bae-824b-131d5a140d1f",
            "answerId": "d613c4e1-cccd-4744-b22b-e2048e6ce94d"
        },
        {
            "questionId": "21674cbb-7ffc-46fd-9c36-7507ac6efd2a",
            "answerId": "b7e07178-6ad3-4201-b349-a058966fa6a0"
        },
        {
            "questionId": "71905c0e-fc23-4c05-b4cb-39df8b5b78bd",
            "answerId": "f10383c2-279a-44b0-8b07-fec77dc330e9"
        }
    ]
}
```
5. call GET `http://localhost:8181/user/98f35cac-600c-45f1-b9f5-12418dd367ef/questionnaire/94cf1c49-b747-4a1b-a03c-c63f4110a54a` to see recommended products

