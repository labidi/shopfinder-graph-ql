## Introduction

``` text
This module is a GraphQl extension for the Shopfinder module. It allows you to : 
 - Get all shops.
 - Update a shop by identifier.
 - Disable deleting a shop by id.
```

## Installation

### Enable module Saddemlabidi_ShopfinderGraphQl

```  bash
php bin/magento module:enable Saddemlabidi_ShopfinderGraphQl
```

### Run setup upgrade

```  bash
php bin/magento setup:upgrade
```

### Run setup:di:compile

```  bash
php bin/magento setup:di:compile
```

## GraphQl Example queries

### Get all shops

```  graphql
{
  shops {
    items {
      id
      name
      address
      city
      country
      postcode
       longitude_latitude
    }
  }
}
```

### Try deleting shop by id

```  graphql
mutation {
  deleteShop(
    id: 1
  )
}
```

### update shop by id

```  graphql
mutation {
  updateShop(input: {
    id: 9,
    identifier: "shop-0013333",
    name: "Updated Shop Name",
    country: "USA",
    image: "shop-001.jpg",
    longitude_latitude: "40.9999,-20.0000"
  }) {
    id
    identifier
    name
    country
    image
    longitude_latitude
    created_at
    updated_at
  }
}
```

### Get shop by identifier

```  graphql
{
    shop(identifier: "shop-0013333") {
        id
        identifier
        name
        country
        image
        longitude_latitude
        created_at
        updated_at
    }
}
```
