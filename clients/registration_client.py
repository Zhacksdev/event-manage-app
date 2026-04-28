import os
import requests
from dotenv import load_dotenv

load_dotenv()

BASE_URL = os.getenv('REGISTRATION_SERVICE_URL', 'http://localhost:8002/api')
TIMEOUT = 5


def get_registration_by_id(registration_id):
    """
    Fetch registration detail from RegistrationService.
    GET /api/registrations/{id}
    Returns dict with registration data or raises Exception.
    """
    url = f'{BASE_URL}/registrations/{registration_id}'
    response = requests.get(
        url,
        headers={'Content-Type': 'application/json'},
        timeout=TIMEOUT,
    )
    response.raise_for_status()
    return response.json()
