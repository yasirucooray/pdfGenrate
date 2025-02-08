# Laravel CSV  and PDF Report Generation

## Features

- Upload CSV files Using API.
- Parse and validate CSV data.
- Store data in a relational database.
- Generate PDF reports from the API endpoit.

## Prerequisites

Ensure you have the following installed on your system:

- **PHP**: Version 8.0 or higher
- **Composer**: Dependency manager for PHP
- **Node.js and npm**: For managing JavaScript dependencies
- **Database**: MySQL or any other database supported by Laravel

## Installation

1. **Clone the Repository**

   ```bash
   git clone git@github.com:yasirucooray/pdfGenrate.git
   cd csvreader

   * composer install
   * create env file
   * setup database details on env file
   * run php artisan:migrate
   * genearate app key php artisan key:generate
   * Run php artisan storage:link for view files.
   * php artisan:serve for run the project 

## Testing

You can test the api endpoints from the postman or insomnia.
There Are two main API endpoints for upload CSV and genrate PDF

 ## Sample endpoint

 * http://127.0.0.1:8000/api/upload
 * http://127.0.0.1:8000/api/generate-pdf

## Additional Information

Sample ER digram and genrated pdf are add into public/Files.
