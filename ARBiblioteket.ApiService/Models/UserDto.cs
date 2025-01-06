
namespace ARBiblioteket.ApiService.Dto
{
    public class UserCreateDto
    {
        public string Username { get; set; } = string.Empty;
        public string Password { get; set; } = string.Empty;
        public string Email { get; set; } = string.Empty;
        public string Department { get; set; } = string.Empty;
    }
    public class UserLoginDto
    {
        public int Id { get; set; }
        public string Email { get; set; }
    }
}
