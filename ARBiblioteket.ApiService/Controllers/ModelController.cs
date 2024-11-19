using Microsoft.AspNetCore.Mvc;

[ApiController]
[Route("api/[controller]")]
public class ModelController : ControllerBase
{
    private readonly IModelService _modelService;

    public ModelController(IModelService modelService)
    {
        _modelService = modelService;
    }

    [HttpGet("{id}")]
    public async Task<ActionResult<Model>> GetModel(int id)
    {
        var model = await _modelService.GetModel(id);
        if (model == null) return NotFound();
        return Ok(model);
    }

    [HttpGet]
    public async Task<ActionResult<List<Model>>> GetAllModels()
    {
        return Ok(await _modelService.GetAllModels());
    }

    [HttpPost]
    public async Task<ActionResult<Model>> CreateModel(ModelDto modelDto)
    {
        var model = await _modelService.CreateModel(modelDto);
        return CreatedAtAction(nameof(GetModel), new { id = model.Id }, model);
    }

    [HttpPut("{id}")]
    public async Task<ActionResult> UpdateModel(int id, ModelDto modelDto)
    {
        var result = await _modelService.UpdateModel(id, modelDto);
        if (!result) return NotFound();
        return NoContent();
    }

    [HttpDelete("{id}")]
    public async Task<ActionResult> DeleteModel(int id)
    {
        var result = await _modelService.DeleteModel(id);
        if (!result) return NotFound();
        return NoContent();
    }
} 