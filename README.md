<!-- GETTING STARTED -->
## Setup

This is an example of how you may give instructions on setting up your project locally.
To get a local copy up and running follow these simple example steps.

### Prerequisites

Ensure that you have php (version 8.1 or greater) and composer installed
  ```sh
  php -v
  ```
  ```sh
  composer -V
  ```

### Running the Application

1. Install Dependencies
   ```sh
   composer install
   ```
2. Copy Example Environment File
   ```sh
   cp .env.example .env
   ```
3. Replace the following environment variables to add the credentials for mock API
   ```env
   INGREDIENT_API_USER
   INGREDIENT_API_PASS
   ```
4. Generate App Key
   ```sh
   php artisan key:generate
   ```
5. Run Migrations
   ```sh
   php artisan migrate
   ```
6. Run the App (Using PHP Built-in Server)
   ```sh
    php artisan serve
   ```

## Database Structure

### 🧾 Recipe Table

Stores the basic information about a recipe.

| Column      | Type      | Description                    |
|-------------|-----------|--------------------------------|
| id          | integer   | Primary key (auto-increment)   |
| name        | string    | Name of the recipe             |
| description | text      | Description of the recipe      |
| created_at  | timestamp | Laravel-managed timestamp      |
| updated_at  | timestamp | Laravel-managed timestamp      |

### 🧂 RecipeIngredient Table

Stores the ingredients used in a recipe.

| Column           | Type    | Description                                  |
|------------------|---------|----------------------------------------------|
| id               | integer | Primary key (auto-increment)                 |
| recipe_id        | integer | Foreign key referencing `recipes.id`         |
| ingredient_name  | string  | Name of the ingredient (from external API)   |
| created_at       | timestamp | Laravel-managed timestamp                  |
| updated_at       | timestamp | Laravel-managed timestamp                  |

**Constraints:**

- A **foreign key** constraint on `recipe_id` references `recipes.id` and **cascades on delete**.
- A **unique constraint** on the combination of `recipe_id` and `ingredient_name` to prevent duplicate ingredients in the same recipe.

## Test API Calls with cURL (Exported From Postman)

### 1. Get Ingredient by Name (Mirror of Mock API)
   ```sh
     curl --location 'http://localhost:8000/api/ingredients?ingredient=Banana' \
--header 'Accept: application/json'
   ```

### 2. Post Ingredient (Mirror of Mock API)
   ```sh
   curl --location 'http://localhost:8000/api/ingredient/Lime' \
--header 'Accept: application/json' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'name=Lemon' \
--data-urlencode 'carbs=6.0' \
--data-urlencode 'fat=3.5' \
--data-urlencode 'protein=0.3'
   ```

### 3. Post Recipe
Checks if the specified ingredients are present in Mock API and if so, upserts (create if not exists otherwise update) the Recipe
   ```sh
   curl --location 'http://localhost:8000/api/recipe/lemon-juice' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
  "description": "Refreshing lemon juice with a hint of mint.",
  "ingredients": ["Lemon", "Lime"]
}'
   ```

### 4. Get Recipe
Fetches the Recipe, while calculating nutritional values based on nutritional values of Ingredients from the Mock API at that time
   ```sh
   curl --location --request GET 'http://localhost:8000/api/recipe/lemon-juice' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
   ```

### 5. Delete Recipe
Deletes the Recipe and its corresponding RecipeIngredients entries
   ```sh
   curl --location --request DELETE 'http://recipe-nutrition-app.test/api/recipe/lemon-juice' \
--header 'Accept: application/json' 
   ```

