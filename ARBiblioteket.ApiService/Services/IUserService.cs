public interface IUserService
{
    Task<User?> GetUser(int id);
    Task<User?> GetUserByUsername(string username);
    Task<List<User>> GetAllUsers();
    Task<User> CreateUser(UserDto userDto);
    Task<bool> UpdateUser(int id, UserDto userDto);
    Task<bool> DeleteUser(int id);
    Task<bool> ValidateUser(string username, string password);
} 