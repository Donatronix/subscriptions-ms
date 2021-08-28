# MemberShip
***
Membership information: types, levels, level options, user affiliation to the above.

##*1. Program type*

##*1.1 Get list all program types*

**GET** [/v1/memberships/admin/program-types][1]

==== Parameters ====

Parameter  | Description | Type | Example | Required
---------  | ----------- | ---- | ------- | --------
id | Program type ID | integer | 1 | Y
name | Program type name | varchar (255) | Midas | Y

=== Request body ===

```
{
    'id': 1,
    'name': 'Midas',
}
```

##*1.2 Create program type*

**POST** [/v1/memberships/admin/program-types][2]

##*1.3 Update program type*

**PUT** [/v1/memberships/admin/program-types/{id}][3]

##*1.4 Remove the program type*

**DELETE** [/v1/memberships/admin/program-types/{id}][4]

##*5 Display a listing of the program types*

**GET** [/v1/memberships/program-types][5]

##*2. Option*

##*2.1 Display a listing of the option*

**GET** [/v1/memberships/admin/opt][6]

==== Parameters ====

Parameter  | Description | Type | Example | Required
---------  | ----------- | ---- | ------- | --------
id | Option ID | integer | 1 | Y
key | Key option |varchar (255) | iste | Y
value | Value option | varchar (255) | Magnam perferendis exercitationem | Y
note | Note option | text | Quod vero velit | Y
level_id | Foreign key linking to the level table. / integer | 1 | Y

=== Request body ===

```
{
    'id': 1,
    'key': 'iste',
    'value': 'Magnam perferendis exercitationem',
    'note': 'Quod vero velit',
    'level_id': 1,
}
```

##*2.2 Create option*

**POST** [/v1/memberships/admin/opt][7]

##*2.3 Update option*

**PUT** [/v1/memberships/admin/opt/{id}][8]

##*2.4 Remove the option*

**DELETE** [/v1/memberships/admin/opt/{id}][9]

##*2.5 Display a listing of the options*

**GET** [/v1/memberships/opt][10]

##*3. Level*

##*3.1 Display a listing of the level*

**GET** [/v1/memberships/admin/levels][11]

==== Parameters ====

Parameter  | Description | Type | Example | Required
---------  | ----------- | ---- | ------- | --------
id | Level ID | integer | 1 | Y
name | Level name | varchar (50) | bronze | Y
price | Level price | double(7,2) | 13.00 | Y
program_type_id | Foreign key linking to the program type table | integer | 1

=== Request body ===

```
{
    'id': 1,
    'name': 'bronze',
    'price': 13.00,
    'program_type_id': 1,
}
```

##*3.2 Create level*

**POST** [/v1/memberships/admin/levels][12]

##*3.3 Update level*

**PUT** [/v1/memberships/admin/levels/{id}][13]

##*3.4 Remove the level*

**DELETE** [/v1/memberships/admin/levels/{id}][14]

##*3.5 Display a listing of the levels*

**GET** [/v1/memberships/levels][15]

##*4. User*

##*4.1 Display a listing of the user*

**GET** [/v1/memberships/][16]

==== Parameters ====

Parameter  | Description | Type | Example | Required
---------  | ----------- | ---- | ------- | --------
id | User uuid | char (36) | 195e1b81-252b-3dfd-bf24-958a9d6304de | Y
program_type_id | Foreign key linking to the program type table | integer | 13 | Y
level_id | Foreign key linking to the level table | integer | 13 | Y
enabled | A safety parameter that disables the user's access to the tariff package | tinyint | 1 | Y

=== Request body ===

```
{
    'id': '195e1b81-252b-3dfd-bf24-958a9d6304de',
    'program_type_id': 13,
    'level_id': 13,
    'enabled': 1,
}
```

##*4.2 Create user*

**POST** [/v1/memberships/admin/users][17]

##*4.3 Update user*

**PUT** [/v1/memberships/admin/users/{id}][18]

##*4.4 Remove the user*

**DELETE** [/v1/memberships/admin/users/{id}][19]

##*4.5 Working with the rewards microservice: getting data, searching in the database, and displaying results.*

**POST** [/v1/memberships/users][20]

=== Input data ===

```
{
    "form_params": {
        'user_id': '195e1b81-252b-3dfd-bf24-958a9d6304de',
        'type': "Pioneer"
    }
}
```


[1]: /v1/memberships/admin/program-types
[2]: /v1/memberships/admin/program-types
[3]: /v1/memberships/admin/program-types/{id}
[4]: /v1/memberships/admin/program-types/{id}
[5]: /v1/memberships/program-types

[6]: /v1/memberships/admin/opt
[7]: /v1/memberships/admin/opt
[8]: /v1/memberships/admin/opt/{id}
[9]: /v1/memberships/admin/opt/{id}
[10]: /v1/memberships/opt

[11]: /v1/memberships/admin/levels
[12]: /v1/memberships/admin/levels
[13]: /v1/memberships/admin/levels/{id}
[14]: /v1/memberships/admin/levels/{id}
[15]: /v1/memberships/levels

[16]: /v1/memberships/admin/users
[17]: /v1/memberships/admin/users
[18]: /v1/memberships/admin/users/{id}
[19]: /v1/memberships/admin/users/{id}
[20]: /v1/memberships/users
