using ARBiblioteket.ApiService.Dto;
using ARBiblioteket.ApiService.Models;

namespace ARBiblioteket.ApiService.Mapping
{
    public class UserMapping
    {
        public User MapCreateUserDtoToUser(UserCreateDto userCreateDto)
        { 
            var user = new User
            {
                Username = userCreateDto.Username,
                Password = userCreateDto.Password,
                Email = userCreateDto.Email,
                Department = userCreateDto.Department
            };
            return user;
        }
        
        public UserLoginDto MapUserToUserLoginDto(User user)
        {
            var userDto = new UserLoginDto
            {
                Id = user.Id,
                Email = user.Email
            };
            return userDto;
        }
    }
}

