
# ConsumerRights backend project

This is a simple booking system create for ConsumerRights for their PHP backend challenge. It is just meant to be an example project and is not intended for any real commercial use.

Once installed and spun up, the user should be able to interact with the created API's either through a web browser, or directly to the API's. 

This is not a full extensive documentation, just an outline for installation and usage. I recommend looking through the code to see all the constraints needed to interact through the API's.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Improvements](#improvements)

## Installation
Before we get started on the installation, this guide assumes that you have PHP 8.2 or higher and MySQL 8 installed and working. You will also need a working knowledge of webservers using something like Apache. The quickest method is to use XAMPP and configure it to run the this repo.

1. Clone the repository:
```
	git clone https://github.com.git
```
2. Open up **MySQL** and run the **SQL** code included in the git repo:
This will create the necessary schema and tables to run. Adjust the schema name if needed, but ensure you remeber the change for the next step.

3. **Create** and **Configure** your .env file:
An example env file has been included with this project. Create a new file env file based on that and change the details to allow access to your MySQL Database. (if you adjusted the schema name then make sure to adjust it here.

4. **Run** the Webserver:
How you do this is dependant on your setup. Once you have spun up a webserver you should be able to access the index.html page from a web browser, or you can interact directly with API's by sending request to them. One of the easiest methods to do this is with Postman, or an equivalent software or service. This will be covered more in the next part.

## Usage
Once installed and set up, it should now be possible to interact with the API's. As mentioned previously this can be done either through a webrowser hitting index.html, or directly through the API's.

The first of the API endpoints is simple. You request a given date and it returns all the booking slots available for that given data in JSON. The available slots is determined by the date and time of day, as well as if there are any existing slots taken on the chosen date. 

>*For example*:
*If the user has chosen the current date, it is 10:05, and there are bookings already made for 15:00 and 15:30, then the response will be this:*
 ```JSON 
["10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","16:00","16:30","17:00","17:30"]
```
The second API endpoint is for making the booking. This API takes in a number of parameters and then makes a booking. The list of all nessacary parameters is in the [API Endpoints](#api-endpoints) section below. 

## API Endpoints
### `/api/get_available_slots.php`

-   **Method**: POST
-   **Description**: Retrieves available slots for a given date.
-   **Parameters**:
    -   `date`: Date for which slots are requested.
-   **Response**:
    -   JSON array of available slots.
- **Example Response**:
 ```JSON 
["10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30"] 
```

### `/api/book_slot.php`

-   **Method**: POST
-   **Description**: Books an appointment slot.
-   **Parameters**:
    -   `date`: Date of the appointment.
    -   `slot`: Selected time slot.
    -   `first_name`: First name of the user.
    -   `last_name`: Last name of the user.
    -   `email`: Email of the user.
    -   `contact_number`: Contact number of the user.
    -   `subject`: Subject of the appointment.
    -   `location` (optional): Location of the appointment.
    -   `comments` (optional): Additional comments.
-   **Response**:
    -   If successful: `{ "message": "Booking successful!" }`
    -   If error: `{ "error": "Error message", "details": "Additional details" }`

## Improvements

There are a number of constraints put on the user to ensure that only valid data is submitted to the database, however there are a few areas in that where the outline was vague in the outline brief, and so I have not fully constrained them as much as possible. 

>An example of this is "contact_number". I have only put basic constraints on it to due to not knowing what format the user was expected to put their number in with, it uses this regular expression as well as allows up to 100 Chars.:
```regex
^[\d\s+()-\.]*$
```
>This is far too many Chars and not enough constraints for a comercial system, but I just set it as is due to not knowing the full constraints needed:

The other part of this that could be more fleshed out, but isn't due to lack of information in the outline is the JSON responses, especially the second API. I was not sure how this data would be used, so I went with just giving a simple confimation or error response. 

Depending on the usage of this system I would instead like to return a response with the data of the booking itself, so that it can then be used for something further, but as it stands sending that data forward as a response doesn't serve a purpose outside of showing the user the data that they have already entered in to make the booking in the first place. 
