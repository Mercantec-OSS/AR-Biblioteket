using System.Net.Http.Json;
using ARBiblioteket.ApiService.Services;
using ARBiblioteket.ApiService.Models;
using ARBiblioteket.ApiService.Dto;
using ARBiblioteket.ApiService.Mapping;

namespace ARBiblioteket.ApiService.Services
{
    public class ApiService
    {
        private readonly HttpClient _httpClient;
        private const string BaseUrl = "api";

        public ApiService(HttpClient httpClient)
        {
            _httpClient = httpClient;
        }

        // OPERATIONS FOR USER
        // CREATE 
        public async Task<User?> RegisterUserAsync(UserCreateDto userDto)
        {
            var response = await _httpClient.PostAsJsonAsync($"{BaseUrl}/user/register", userDto);
            if (response.IsSuccessStatusCode)
            {
                return await response.Content.ReadFromJsonAsync<User>();
            }
            return null;
        }

        public async Task<UserLoginDto?> LoginAsync(string email, string password)
        {
            var response = await _httpClient.PostAsJsonAsync($"{BaseUrl}/user/login", new { email, password });
            if (response.IsSuccessStatusCode)
            {
                return await response.Content.ReadFromJsonAsync<UserLoginDto>();
            }
            return null;
        }


        //READ
        public async Task<User?> GetUserAsync(int id)
        {
            return await _httpClient.GetFromJsonAsync<User>($"{BaseUrl}/user/{id}");
        }

        public async Task<List<User>?> GetAllUsersAsync()
        {
            return await _httpClient.GetFromJsonAsync<List<User>>($"{BaseUrl}/user");
        }

        // UPDATE 
        public async Task<bool> UpdateUserAsync(int id, UserCreateDto userDto)
        {
            var response = await _httpClient.PutAsJsonAsync($"{BaseUrl}/user/{id}", userDto);
            return response.IsSuccessStatusCode;
        }

        // DELETE 
        public async Task<bool> DeleteUserAsync(int id)
        {
            var response = await _httpClient.DeleteAsync($"{BaseUrl}/user/{id}");
            return response.IsSuccessStatusCode;
        }

        // OPERATIONS FOR MODEL
        // CREATE operation
        public async Task<Model?> CreateModelAsync(ModelCreateDto modelDto)
        {
            var response = await _httpClient.PostAsJsonAsync($"{BaseUrl}/model", modelDto);
            if (response.IsSuccessStatusCode)
            {
                return await response.Content.ReadFromJsonAsync<Model>();
            }
            return null;
        }

        // READ 
        public async Task<Model?> GetModelAsync(int id)
        {
            return await _httpClient.GetFromJsonAsync<Model>($"{BaseUrl}/model/{id}");
        }

        public async Task<List<Model>?> GetAllModelsAsync()
        {
            return await _httpClient.GetFromJsonAsync<List<Model>>($"{BaseUrl}/model");
        }

        // UPDATE 
        public async Task<bool> UpdateModelAsync(int id, ModelUpdateDto modelDto)
        {
            var response = await _httpClient.PutAsJsonAsync($"{BaseUrl}/model/{id}", modelDto);
            return response.IsSuccessStatusCode;
        }

        // DELETE 
        public async Task<bool> DeleteModelAsync(int id)
        {
            var response = await _httpClient.DeleteAsync($"{BaseUrl}/model/{id}");
            return response.IsSuccessStatusCode;
        }
    }
} 