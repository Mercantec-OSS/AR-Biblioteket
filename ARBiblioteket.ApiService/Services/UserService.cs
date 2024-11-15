public class UserService : IUserService
{
    private readonly List<User> _users = new();

    public async Task<User?> GetUser(int id)
    {
        return await Task.FromResult(_users.FirstOrDefault(u => u.Id == id));
    }

    public async Task<User?> GetUserByUsername(string username)
    {
        return await Task.FromResult(_users.FirstOrDefault(u => u.Username == username));
    }

    public async Task<List<User>> GetAllUsers()
    {
        return await Task.FromResult(_users);
    }

    public async Task<User> CreateUser(UserDto userDto)
    {
        var user = new User
        {
            Id = _users.Count + 1,
            Username = userDto.Username,
            Email = userDto.Email,
            Password = userDto.Password
        };
        
        _users.Add(user);
        return await Task.FromResult(user);
    }

    public async Task<bool> UpdateUser(int id, UserDto userDto)
    {
        var user = await GetUser(id);
        if (user == null) return false;
        
        user.Username = userDto.Username;
        user.Email = userDto.Email;
        user.Password = userDto.Password;
        
        return true;
    }

    public async Task<bool> DeleteUser(int id)
    {
        var user = await GetUser(id);
        if (user == null) return false;
        
        _users.Remove(user);
        return true;
    }

    public async Task<bool> ValidateUser(string username, string password)
    {
        var user = await GetUserByUsername(username);
        if (user == null) return false;
        
        return user.Password == password;
    }
} 