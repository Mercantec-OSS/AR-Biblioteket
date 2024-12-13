using ARBiblioteket.ApiService.Services;
using ARBiblioteket.ApiService.Models;
using ARBiblioteket.ApiService.Mapping;
using ARBiblioteket.ApiService.Dto;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

[ApiController]
[Route("api/[controller]")]
public class UserController : ControllerBase
{
    private readonly ApplicationDbContext _DbContext;
    private readonly UserMapping _userMapping;

    public UserController(ApplicationDbContext DbContext, UserMapping userMapping)
    {
        _DbContext = DbContext;
        _userMapping = userMapping;
    }
    //Get user by ID 
    [HttpGet("{id}")]
    public async Task<ActionResult<User>> GetUser(int id)
    {
        var user = await _DbContext.Users.FindAsync(id);
        if (user == null) return NotFound();
        return Ok(user);
    }
    //Get all users
    [HttpGet]
    public async Task<ActionResult<List<User>>> GetAllUsers()
    {
        return Ok(await _DbContext.Users.ToListAsync());
    }
    //Register user
    [HttpPost("register")]
    public async Task<ActionResult<User>> Register(UserCreateDto userDto)
    {
        if (userDto == null)
        {
            return BadRequest("User data is required.");
        }

        try
        {
            _DbContext.Users.Add(_userMapping.MapCreateUserDtoToUser(userDto));
            await _DbContext.SaveChangesAsync();
            return Ok(userDto);
        }
        catch (Exception ex)
        {
            return BadRequest($"Failed to register {ex.Message}");
        }
    }
    //Login user
    [HttpPost("login")]
    public async Task<ActionResult<UserLoginDto>> Login(string email, string password)
    {
        var user = await _DbContext.Users.Where(e => e.Email == email).FirstOrDefaultAsync(p => p.Password == password);
        if (user == null)
        {
            return NotFound();
        }
        return Ok(_userMapping.MapUserToUserLoginDto(user));
    }
    //Edit user by ID
    [HttpPut("{id}")]
    public async Task<IActionResult> UpdateUser(int id, UserCreateDto userCreateDto)
    {
        var result = await _DbContext.Users.FindAsync(id);
        if (result== null)
        {
            return NotFound(); 
        }
        
        result.Email = userCreateDto.Email ?? throw new ArgumentException(nameof(userCreateDto.Email));
        result.Password = userCreateDto.Password ?? throw new ArgumentException(nameof(userCreateDto.Password));
        result.Username = userCreateDto.Username ?? throw new ArgumentException(nameof(userCreateDto.Username));
        result.Department = userCreateDto.Department ?? throw new ArgumentException(nameof(userCreateDto.Department));
        
        _DbContext.Entry(result).State = EntityState.Modified;

        try
        {
            await _DbContext.SaveChangesAsync();
        }
        catch
        {
            if (!_DbContext.Users.Any(u => u.Id == id))
            {
                return NotFound();
            }
        }
        return Ok();
    }
    //Delete user by ID
    [HttpDelete("{id}")]
    public async Task<ActionResult> DeleteUser(int id)
    {
        var result = await _DbContext.Users.FindAsync(id);
        if (result == null)
        {
            return NotFound();
        }
        _DbContext.Users.Remove(result);
        await _DbContext.SaveChangesAsync();
        return Ok();
    }   
} 