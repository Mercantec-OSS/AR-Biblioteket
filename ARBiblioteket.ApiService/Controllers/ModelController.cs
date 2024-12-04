using ARBiblioteket.ApiService.Services;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

[ApiController]
[Route("api/[controller]")]
public class ModelController : ControllerBase
{
    private readonly ApplicationDbContext _DbContext;
    private readonly ModelMapping _modelMapping;

    public ModelController(ApplicationDbContext DbContext, ModelMapping modelMapping)
    {
        _DbContext = DbContext;
        _modelMapping = modelMapping;
    }
    //Get model by ID
    [HttpGet("{id}")]
    public async Task<ActionResult<Model>> GetModel(int id)
    {
        var model = await _DbContext.Models.FindAsync(id);
        if (model == null) return NotFound();
        return Ok(model);
    }
    //Get all models
    [HttpGet]
    public async Task<ActionResult<List<Model>>> GetAllModels()
    {
        return Ok(await _DbContext.Models.ToListAsync());
    }
    //Create model
    [HttpPost]
    public async Task<ActionResult<Model>> CreateModel(ModelCreateDto modelDto)
    {
        if (modelDto == null)
            return BadRequest();

        try
        {
            var model = _modelMapping.MapCreateModelDtoToModel(modelDto);
            _DbContext.Models.Add(model);
            await _DbContext.SaveChangesAsync();
            return Ok(model);
        }
        catch (Exception ex)
        {
            return BadRequest(ex.Message);
        }
    }
    //Edit model by ID
    [HttpPut("{id}")]
    public async Task<IActionResult> UpdateModel(int id, ModelUpdateDto modelUpdateDto)
    {
        var result = await _DbContext.Models.FindAsync(id);
        if (result == null)
        {
            return NotFound();
        }

        result.Title = modelUpdateDto.Title ?? throw new ArgumentException(nameof(modelUpdateDto.Title));
        result.Description = modelUpdateDto.Description ?? throw new ArgumentException(nameof(modelUpdateDto.Description));
        result.Education = modelUpdateDto.Education ?? throw new ArgumentException(nameof(modelUpdateDto.Education));
        result.ThreeDFile = modelUpdateDto.ThreeDFile ?? throw new ArgumentException(nameof(modelUpdateDto.ThreeDFile));
        result.ImageFile = modelUpdateDto.ImageFile ?? throw new ArgumentException(nameof(modelUpdateDto.ImageFile));
        result.LastEdited = DateOnly.FromDateTime(DateTime.UtcNow);

        _DbContext.Entry(result).State = EntityState.Modified;

        try
        {
            await _DbContext.SaveChangesAsync();
            return Ok(result);
        }
        catch
        {
            if (!_DbContext.Models.Any(m => m.Id == id))
            {
                return NotFound();
            }
            throw;
        }
    }
    //Delete user by ID
    [HttpDelete("{id}")]
    public async Task<ActionResult> DeleteModel(int id)
    {
        var model = await _DbContext.Models.FindAsync(id);
        if (model == null)
        {
            return NotFound();
        }

        _DbContext.Models.Remove(model);
        await _DbContext.SaveChangesAsync();
        return NoContent();
    }
} 