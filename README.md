# microservices-vehicles
Symfony API app, simple task with manytoone relation.
It is based job apply task.

## Model
Vehicle [id, brand, model, year] -> ManyToOne -> VehicleOwner [id, name, address]
orphan Removal : true

## Description
PHP api interface for model above based on Symfony.
There is no used other packages e.g. APIplatform.
PHP 8.2+, MySQL

Im usign modified serializers for Vehicles and VehicleOwners to avoid circular reference.
For PATCH methods im not using createForm() method, instead im using deserialize method and object_to_populate argument.
Validation of DTO for PATCH methods is using whole model, not just updated fields.

Added just simple tests.


### Endpoints & payload

**Vehicles**

Method: POST 
URL: /api/vehicleowners/{ownerId}/vehicles/
Payload:
```
{
    "brand": string,
    "model": string,
    "year": int,
}
```
Response: 201
```
{
    "id": int,
    "brand": string,
    "model": string,
    "year": int,
}
```

Method: GET
URL: /api/vehicles/{vehicleId}/
Response: 200
```
{
    "id": int,
    "brand": string,
    "model": string,
    "year": int,
    "vehicleOwner": {
        "id": int,
        "name": string,
        "address": string
    }
}
```
Method: GET
URL: /api/vehicleowners/{ownerId}/vehicles/
Response: 200
```
[
{
    "id": int,
    "brand": string,
    "model": string,
    "year": int,
}
]
```

Method: DELETE
URL: /api/vehicles/{vehicleId}/, /api/vehicleowners/{ownerId}/vehicles/{vehicleId}/
Response: 204

Method: PATCH
URL: /api/vehicles/{vehicleId}/, /api/vehicleowners/{ownerId}/vehicles/{vehicleId}/
Payload:
```
{
    "brand": string (optional),
    "model": string (optional),
    "year": int (optional,
    "vehicleOwner" : (optional - for change the owner)
      "id": int
}
```
Response: 202

**Vehicle owner**

Method: POST
URL: /api/vehicleowners/
Payload:
```
{
  "name": string,
  "address": string
}
```
Response: 201

Method: GET
URL: /api/vehicleowners/{ownerId}
Response: 200
```
{
  "id": int,
  "name": string,
  "address": string
}
```
Method: DELETE
URL: /api/vehicleowners/{ownerId}/
Response: 204
  
Method: PATCH
URL: /api/vehicleowners/{ownerId}/
Payload:
```
{
  "name": string (optional)
  "address": string (optional, at least one of optionals)
}
```
Response: 202
```
{
  "id": int,
  "name": string,
  "address": string
}
```
