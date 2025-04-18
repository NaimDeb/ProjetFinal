import { Extension, Game } from '@/types/gameType';
import apiClient from './apiClient';
import { Patchnote } from '@/types/patchNoteType';

class GameService {


    async getGameById(id: string): Promise<Game> {
        const response = await apiClient.get(`/games/${id}`);
        return response.data;
    }

    async getGameExtensions(id: string): Promise<Array<Extension>> {
        const response = await apiClient.get(`/games/${id}/extensions`);
        return response.data.member;
    }

    async getGamePatchNotes(id: string): Promise<Array<Patchnote>> {
        const response = await apiClient.get(`/games/${id}/patchnotes`);
        return response.data.member;
    }

    async getPatchNoteById(id: string): Promise<Patchnote> {
        const response = await apiClient.get(`/patchnotes/${id}`);
        return response.data;
    }

    async patchPatchnote(id: string, patchnoteData: Partial<Patchnote>): Promise<Patchnote> {
        const config = { headers: { "Authorization": `Bearer ${getToken()}` } };

        const response = await apiClient.patch(`/patchnotes/${id}`, patchnoteData, config);
        return response.data;
    }

    async postPatchnote(patchnoteData: Partial<Patchnote>): Promise<Patchnote> {
        const config = { headers: { "Authorization": `Bearer ${getToken()}` } };

        const response = await apiClient.post(`/patchnotes`, patchnoteData, config);

        return response.data;
    }
    

}

function getToken() {
    const token = document.cookie.split('; ').find(row => row.startsWith('auth_token='))?.split('=')[1];
    if (!token) {
        throw new Error('No token found in local storage');
    }
    return token;
}

const gameService = new GameService();
export default gameService;