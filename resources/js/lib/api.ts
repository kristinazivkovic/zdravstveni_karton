import axios from 'axios';

const API_URL = 'api/user';

export interface User {
    id: number;
    name: string;
    email: string;
    // Dodajte ostala polja po potrebi
}

// Dohvati sve korisnike
export const getUsers = async (): Promise<User[]> => {
    const response = await axios.get<User[]>(API_URL);
    return response.data;
};

// Dohvati korisnika po ID-u
export const getUser = async (id: number): Promise<User> => {
    const response = await axios.get<User>(`${API_URL}/${id}`);
    return response.data;
};

// Kreiraj novog korisnika
export const createUser = async (user: Omit<User, 'id'>): Promise<User> => {
    const response = await axios.post<User>(API_URL, user);
    return response.data;
};

// Ažuriraj korisnika
export const updateUser = async (id: number, user: Partial<User>): Promise<User> => {
    const response = await axios.put<User>(`${API_URL}/${id}`, user);
    return response.data;
};

// Obriši korisnika
export const deleteUser = async (id: number): Promise<void> => {
    await axios.delete(`${API_URL}/${id}`);
};