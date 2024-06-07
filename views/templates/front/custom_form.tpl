{if isset($confirmation)}
<p class="alert alert-success"><pre>{$confirmation|@var_dump}</pre></p>
{/if}
{if isset($error)}
<p class="alert alert-danger">{$error}</p>
{/if}