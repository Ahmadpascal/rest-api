import { apiRequest } from '../apiClient';

export async function registerApi(payload) {
    // payload: { name, email, password, password_confirmation }
    return apiRequest('/register', {
        method: 'POST',
        body: JSON.stringify(payload),
    });
}

export async function loginApi(payload) {
    // payload: { email, password }
    return apiRequest('/login', {
        method: 'POST',
        body: JSON.stringify(payload),
    });
}

export async function logoutApi(token) {
    return apiAuthRequest('/logout', token, {
        method: 'POST',
    });
}