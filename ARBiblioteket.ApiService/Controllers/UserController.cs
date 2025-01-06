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
    public async Task<ActionResult<User>> Register([FromBody] User user)
    {
        if (user == null)
        {
            return BadRequest("User data is required.");
        }

        try
        {
            _DbContext.Users.Add(user);
            await _DbContext.SaveChangesAsync();
            return Ok(user);
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
        return Ok((user));
    }
    //Edit user by ID
    [HttpPut("{id}")]
    public async Task<IActionResult> UpdateUser([FromRoute] int id, [FromBody] User user)
    {
        var result = await _DbContext.Users.FindAsync(id);
        if (result== null)
        {
            return NotFound(); 
        }

        result.Email = user.Email;
        result.Password = user.Password;
        result.Username = user.Username;
        result.Department = user.Department;
        
        _DbContext.Entry(result).State = EntityState.Modified;

        _DbContext.Users.Update(result);
        await _DbContext.SaveChangesAsync();

        return Ok(result);
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